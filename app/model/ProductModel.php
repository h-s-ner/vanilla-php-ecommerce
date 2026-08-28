<?php require_once __DIR__.'/../db/DB.php';

class ProductModel extends DB
{
        public function index(): array
        {
                $sql = "SELECT * FROM products WHERE active = 1 and stock > 0";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll();
        }

        public function adminIndex(): array
        {
                $sql = "SELECT * FROM products";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                //fetchAll gibt immer ein Array zurück
                //Wenn keine Daten → leeres Array []
                return $stmt->fetchAll();
        }

        public function show($id): ?array
        {
                $sql =  "SELECT * FROM products
                        WHERE id = :id AND active = 1 AND stock > 0";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch();
                return $result ?: null;
        }

        public function adminShow($id): ?array
        {
                $sql = "SELECT * FROM products WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // Fetch gibt einzelnen Datensatz zurück
                // wenn nicht gefunden → false
                $result = $stmt->fetch();

                return $result ?: null;
        }

        public function getProductStockById($product_id): int
        {

                $sql = "SELECT stock FROM products WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':id', $product_id, PDO::PARAM_INT);
                $stmt->execute();
                return (int) $stmt->fetchColumn();
        }

        public function destroy($id): bool
        {
                $sql = "DELETE FROM products WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
        }

        public function store($name, $price, $description, $stock, $active,$image): int
        {

                $sql = "INSERT INTO products (name, price, stock, description, active, image,created_at)
                        VALUES (:name, :price, :stock, :description, :active, :image,NOW())";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                        ':name' => $name,
                        ':price' => $price,
                        ':stock' => $stock,
                        ':description' => $description,
                        ':active' => $active,
                        ':image' => $image
                ]);
                return (int) $this->conn->lastInsertId();
        }

        public function update($id, $name, $price, $description, $stock, $active): bool
        {

                $sql = "UPDATE products SET
                name = :name,
                price = :price,
                stock = :stock,
                description = :description,
                active = :active
                WHERE id = :id";

                $stmt = $this->conn->prepare($sql);

                return $stmt->execute([
                        ':id' => $id,
                        ':name' => $name,
                        ':price' => $price,
                        ':stock' => $stock,
                        ':description' => $description,
                        ':active' => $active
                ]);

        }

        public function updateImage($id, $image): bool
        {
                $sql = "UPDATE products SET image = :image WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                return $stmt->execute([
                        ':id' => $id,
                        ':image' => $image
                ]);
        }

        public function updateStock($id, $stock): bool
        {
                $sql = "UPDATE products SET stock = :stock WHERE id = :id";
                $stmt = $this->conn->prepare($sql);

                return $stmt->execute([
                        ':id' => $id,
                        ':stock' => $stock
                ]);
        }

        public function getStartseiteProducts()
        {
                $sql = "SELECT *
                        FROM products
                        where active=1
                           and stock > 0
                           and created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
                           ORDER BY created_at DESC";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll();
        }
        public function getPageProducts($page,$limit)
        {

                $sql = "SELECT *
                        FROM products
                        where active=1
                           and stock > 0
                        ORDER BY id ASC
                        LIMIT :limit OFFSET :offset";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)(($page-1)*$limit), PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll();
        }
        public function getProductsBySearch($search)
        {
                $sql = "SELECT * FROM products p
                        WHERE p.name LIKE :search
                        OR p.brand_name LIKE :search
                        OR p.description LIKE :search
                        ORDER BY p.name
                ";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                        ':search'=>'%'.$search.'%'
                ]);
                return $stmt->fetchAll();
        }
        public function getProductCount(): int
        {

                $sql = "SELECT count(*) FROM products WHERE active=1 and stock > 0";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute();
                return (int) $stmt->fetchColumn();
        }
}