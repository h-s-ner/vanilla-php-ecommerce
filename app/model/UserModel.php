<?php require_once __DIR__.'/../db/DB.php';

class UserModel extends DB{


    public function findById($id) :array|bool{
        $sql = "SELECT * FROM users WHERE id= :id";
        $ps = $this->conn->prepare($sql);
        $ps->bindParam(':id',$id);
        $ps->execute();
        return $ps->fetch();
    }
    // zu sätzlich schauen ob email geändert wurde, dann sollte noch mal ein mail geschickt werden
    public function updateUserDetails($id, $fname, $lname, $phone){
        $sql = "Update users set fname = :fname, lname = :lname, phone = :phone,  WHERE id= :id";
        $ps = $this->conn->prepare($sql);
        $ps->execute([
            ':id' => $id,
            ':fname' => $fname,
            ':lname' => $lname,
            ':phone' => $phone,
        ]);
    }

    public function getAllUsers(){
        $sql = "SELECT id, fname, lname, email, active FROM users" ;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateUserStatus($id, $status){
        $sql = "Update users set active=:active WHERE id= :id";
        $ps = $this->conn->prepare($sql);
        $ps->execute([
            ':id' => $id,
            ':active' => $status
        ]);

    }

    public function storeAddress($user_id, $type, $adress, $city, $zip){
        $sql = "INSERT INTO addresses(user_id, type, address, city, zip)
                VALUES(:user_id, :type, :adress, :city, :zip)";
        $ps = $this->conn->prepare($sql);
        $ps->execute([
            ":user_id" => $user_id,
            ":type" => $type,
            ":adress" => $adress,
            ":city" => $city,
            ":zip" => $zip
        ]);
    }

    public function getAddresses($user_id, $type): array
    {
        $sql = "SELECT * FROM addresses WHERE user_id = :user_id and type = :type";
        $ps = $this->conn->prepare($sql);
        $ps->execute([
            ':user_id' => $user_id,
            ':type' => $type
        ]);
        return $ps->fetchAll();
    }
}
?>