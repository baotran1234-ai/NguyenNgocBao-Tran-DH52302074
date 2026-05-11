<?php
// app/controllers/SearchController.php
class SearchController {
    public function index(): void {
        require_once APP_PATH . '/views/admin/layout.php';
        echo '<div style="padding:40px;text-align:center"><h2>SearchController</h2><p>Module dang pht tri?n...</p></div>';
        require_once APP_PATH . '/views/admin/layout_end.php';
    }
    public function create(): void { $this->index(); }
    public function edit($id=null): void { $this->index(); }
    public function delete($id=null): void { redirect(url('admin/dashboard')); }
    public function detail($id=null): void { $this->index(); }
    public function profile(): void { $this->index(); }
    public function orders(): void { $this->index(); }
    public function orderDetail($id=null): void { $this->index(); }
    public function wishlist(): void { $this->index(); }
    public function changePassword(): void { $this->index(); }
}

