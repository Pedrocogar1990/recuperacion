<?php

namespace Src;

use \PDO;
use \PDOException;

class Usuario extends Conexion
{

    private $id;
    private $email;
    private $perfil;
    private $ciudad;
    private $pass;

    public function __construct()
    {
        parent::__construct();
    }
    //----------------------------------CRUD------------------------------------------
    public function create()
    {
        $q = "insert into usuarios(email,pass,ciudad,perfil) values(:e,:p,:c,:pe)";
        $stmt = parent::$conexion->prepare($q);

        try {
            $stmt->execute([
                ':e' => $this->email,
                ':p' => $this->pass,
                ':c' => $this->ciudad,
                ':pe' => $this->perfil
            ]);
        } catch (\PDOException $ex) {
            die("Error en create():" . $ex->getMessage());
        }
    }
    public static function read()
    {
        parent::crearConexion();
        $q = "select * from usuarios order by id desc";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (\PDOException $ex) {
            die("Error en read:" . $ex->getMessage());
        }
        parent::$conexion = null;

        return $stmt->fetchall(PDO::FETCH_OBJ);
    }

    public static function update($email)
    {
        $perfil = (self::isAdmin($email)) ? "Usuario" : "Administrador";

        parent::crearConexion();
        $q = "update usuarios set perfil=:p where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':p'=>$perfil,
                ':e'=>$email
            ]);
        } catch (\PDOException $ex) {
            die("Error en uodate:".$ex->getMessage());
        }
        self::$conexion = null;
    }
    //----------------------------------otros metodos------------------------------------------
    public static function comprobarCredenciales($email, $pass)
    {
        parent::crearConexion();
        $q = "select pass from usuarios where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':e' => $email
            ]);
        } catch (\PDOException $ex) {
            die("Error en comprobarCredenciales():" . $ex->getMessage());
        }
        parent::$conexion = null;

        if ($stmt->rowCount() == 0) return false;

        $passBD = $stmt->fetch(PDO::FETCH_OBJ)->pass;

        return password_verify($pass, $passBD);
    }
    public static function devolverCiudades()
    {
        return ["Almeria", "Cadiz", "Cordoba", "Granada", "Huelva", "Jaen", "Malaga", "Sevilla"];
    }
    public static function existeEmail($email)
    {
        parent::crearConexion();
        $q = "select id from usuarios where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':e' => $email,
            ]);
        } catch (\PDOException $ex) {
            die("Error en existeEmail" . $ex->getMessage());
        }

        parent::$conexion = null;

        return $stmt->rowCount();
    }
    public static function devolverEmail($id): ?string
    {
        parent::crearConexion();
        $q = "select email from usuarios where id=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':i' => $id,
            ]);
        } catch (\PDOException $ex) {
            die("Error en existeEmail" . $ex->getMessage());
        }

        parent::$conexion = null;
        return $stmt->fetch(PDO::FETCH_OBJ)->email;
    }
    public static function isAdmin($email): bool
    {
        parent::crearConexion();
        $q = "select perfil from usuarios where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':e' => $email
            ]);
        } catch (\PDOException $ex) {
            die("Error en isAdmin():" . $ex->getMessage());
        }

        parent::$conexion = null;
        $perfil = $stmt->fetch(PDO::FETCH_OBJ)->perfil;
        return ($perfil == "Administrador");
    }
    //----------------------------------setters------------------------------------------
    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of perfil
     *
     * @return  self
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Set the value of ciudad
     *
     * @return  self
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Set the value of pass
     *
     * @return  self
     */
    public function setPass($pass)
    {
        $this->pass = password_hash($pass, PASSWORD_DEFAULT);

        return $this;
    }
}
