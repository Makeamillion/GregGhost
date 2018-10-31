<?php


class DB {

    public $conn;

    public function __construct() {
        $host = 'localhost';
        $dbName = 'snake_of_pi';
        $user = 'root';
        $pass = '';

        $this->conn = new PDO('mysql:dbname='.$dbName.';host='.$host, $user, $pass);
    }

    // TODO :: подписать функции, чтобы было понятнее
    // TODO :: вынести пользователя в отдельный модуль

    /*User*/
    public function getUsers() {
        $query = 'SELECT * FROM user ORDER BY id DESC';
        return $this->conn->query($query)->fetchAll(PDO::FETCH_CLASS);
    }
    // Получить пользователя по логину
    public function getUserByLogin($login) {
        $sql = 'SELECT * FROM user WHERE login = :login ORDER BY id DESC LIMIT 1';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':login', $login, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchObject('stdClass');
    }
    // Получить пользователя по токену
    public function getUserByToken($token) {
        $sql = 'SELECT * FROM user WHERE token = :token ORDER BY id DESC LIMIT 1';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':token', $token, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchObject('stdClass');
    }
    // Получить пользователя
    public function getUser($login, $password) {
        $sql = 'SELECT * FROM user WHERE login = :login and password = :password ORDER BY id DESC LIMIT 1';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':login', $login, PDO::PARAM_STR);
        $stm->bindValue(':password', $password, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchObject('stdClass');
    }
     // Обновить токен пользователя
    public function updateUserToken($id, $token) {
        $token = bin2hex(random_bytes(32));

        $sql = "UPDATE user SET token = :token WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->fetchObject('stdClass');
    }
    // Создать пользователя
    public function createUser($options) {
        $name = $options['name'];
        $login = $options['login'];
        $password = $options['password'];
        $token = bin2hex(random_bytes(32));

        $sql = "INSERT INTO user (name, login, password, token) VALUES (:name, :login, :password, :token)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        return $stmt->fetchObject('stdClass');
    }



    /*Snake*/
    // Получить удава
    public function getSnakes() {
        $query = 'SELECT * FROM snake';
        return $this->conn->query($query)->fetchAll(PDO::FETCH_CLASS);
    }
    // Получить удава пользователя
    public function getUserSnakes($user_id) {
        $sql = 'SELECT * FROM snake, user WHERE user.id = :user_id AND snake.user_id=user.id ORDER BY snake.id DESC';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_CLASS);
    }
    // Получить змею по id
    public function getSnakeById($id) {
        $sql = 'SELECT * FROM snake WHERE id = :id';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchObject('stdClass');
    }
    // Создать змею
    public function createSnake($options) {
        $user_id = $options['user_id'];
        $direction = $options['direction'];

        $sql = "INSERT INTO snake (user_id, direction, body) VALUES (:user_id, :direction, :body)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':direction', $direction, PDO::PARAM_STR);
        return $stmt->execute();
    }
    // Удалить змею
    public function deleteSnake($id) {
        $sql = "DELETE FROM snake WHERE id =  :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // Удалить змею пользователя
    public function deleteUserSnakes($user_id) {
        $sql = "DELETE FROM snake WHERE user_id =  :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /*Snake_body*/
    // Получить тело змеи
    public function getSnakeBody($id) {
        $sql = 'SELECT * FROM snake_body WHERE snake_id = :id';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_CLASS);
    }
    // Создать тело змеи
    public function createSnakeBody($options) {
        $snake_id = $options['snake_id'];
        $x = $options['x'];
        $y = $options['y'];

        $sql = "INSERT INTO snake_body (snake_id, x, y) VALUES (:snake_id, :x, :y)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':snake_id', $snake_id, PDO::PARAM_INT);
        $stmt->bindParam(':x', $x, PDO::PARAM_INT);
        $stmt->bindParam(':y', $y, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // Удалить тело змеи
    public function deleteSnakeBody($id) {
        $sql = "DELETE FROM snake_body WHERE id =  :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // Удалить часть тела змеи
    public function deleteSnakeBodyFromSnake($id) {
        $sql = "DELETE FROM snake_body WHERE snake_id =  :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }




    /*Food*/
    // Получить еду
    public function getFoods() {
        $query = 'SELECT * FROM food';
        return $this->conn->query($query)->fetchAll(PDO::FETCH_CLASS);
    }
    // Удалить еду
    public function deleteFood($id) {
        $sql = "DELETE FROM food WHERE id =  :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }



    /*System*/
    public function getSystemByName($name) {
        $sql = 'SELECT * FROM system WHERE name = :name';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':name', $name, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetchObject('stdClass');
    }
    // Создать систему
    public function createSystem($name, $value) {
        $stmt = $this->conn->prepare("INSERT INTO system (name, value) VALUES (:name, :value)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /*Map*/
    // Получить карту
    public function getMaps() {
        $query = 'SELECT * FROM map';
        return $this->conn->query($query)->fetchAll(PDO::FETCH_CLASS);
    }
    // Получить карту по Id
    public function getMapById($id) {
        $sql = 'SELECT * FROM map WHERE id = :id';
        $stm = $this->conn->prepare($sql);
        $stm->bindValue(':id', $id, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchObject('stdClass');
    }
    // Создать карту
    public function createMap($options) {
        $width = $options['width'];
        $height = $options['height'];

        $sql = "INSERT INTO map (width, height) VALUES (:width, :height)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':width', $width, PDO::PARAM_INT);
        $stmt->bindValue(':height', $height, PDO::PARAM_INT);
        return $stmt->execute();
    }



}
