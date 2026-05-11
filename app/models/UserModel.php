<?php
// ================================================================
// app/models/UserModel.php
// ================================================================
class UserModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO users (name,email,password,phone,is_active)
            VALUES (:name,:email,:password,:phone,1)
        ");
        $stmt->execute([
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT, ['cost'=>12]),
            ':phone'    => $data['phone'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $params = [':id' => $id];
        $allowed = ['name','phone','gender','birthday','address','city','district','ward'];
        foreach ($allowed as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "$f=:$f";
                $params[":$f"] = $data[$f];
            }
        }
        if (empty($fields)) return false;
        $sql = "UPDATE users SET " . implode(',', $fields) . ", updated_at=NOW() WHERE id=:id";
        return $this->db->prepare($sql)->execute($params);
    }

    public function updatePassword(int $id, string $password): bool {
        return $this->db->prepare("UPDATE users SET password=:pass WHERE id=:id")
            ->execute([':pass' => password_hash($password, PASSWORD_BCRYPT, ['cost'=>12]), ':id' => $id]);
    }

    public function updateAvatar(int $id, string $path): bool {
        return $this->db->prepare("UPDATE users SET avatar=:avatar WHERE id=:id")
            ->execute([':avatar' => $path, ':id' => $id]);
    }

    public function updateLastLogin(int $id): void {
        $this->db->prepare("UPDATE users SET last_login=NOW() WHERE id=:id")->execute([':id'=>$id]);
    }

    public function setResetToken(string $email, string $token): bool {
        return $this->db->prepare("UPDATE users SET reset_token=:token, reset_expires=DATE_ADD(NOW(),INTERVAL 1 HOUR) WHERE email=:email")
            ->execute([':token' => hash('sha256',$token), ':email' => $email]);
    }

    public function findByResetToken(string $token): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token=:token AND reset_expires > NOW()");
        $stmt->execute([':token' => hash('sha256',$token)]);
        return $stmt->fetch() ?: null;
    }

    public function clearResetToken(int $id): void {
        $this->db->prepare("UPDATE users SET reset_token=NULL, reset_expires=NULL WHERE id=:id")->execute([':id'=>$id]);
    }

    public function getAll(int $page=1, int $perPage=20): array {
        $offset = ($page-1)*$perPage;
        $total  = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stmt   = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT :l OFFSET :o");
        $stmt->bindValue(':l', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':o', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['data' => $stmt->fetchAll(), 'total' => $total];
    }

    public function toggleStatus(int $id): void {
        $this->db->prepare("UPDATE users SET is_active = 1-is_active WHERE id=:id")->execute([':id'=>$id]);
    }

    public function delete(int $id): bool {
        return $this->db->prepare("DELETE FROM users WHERE id=:id")->execute([':id'=>$id]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}

// ================================================================
// app/models/AdminModel.php
// ================================================================
class AdminModel {
    private PDO $db;
    public function __construct() { $this->db = db(); }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE email=:email AND is_active=1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function updateLastLogin(int $id): void {
        $this->db->prepare("UPDATE admins SET last_login=NOW() WHERE id=:id")->execute([':id'=>$id]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
}
