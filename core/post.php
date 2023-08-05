<?php
// This represents my model which is the mapping of my database
class Post
{
    //db stuff
    private $conn;
    private $table = 'posts';

    //post properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $create_at;

    //contructor with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //getting posts from our database 
    public function read()
    {
        //create query
        $query = 'SELECT
            c.name as category_name,
            p.id,
            p.category_id,
            p.title,
            p.body,
            p.author,
            p.created_at
            FROM
            ' . $this->table . ' p
            LEFT JOIN
            categories c ON p.category_id = c.id ORDER BY p.created_at DESC';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //execute query
        $stmt->execute();
        return $stmt;
    }


    public function delete($id)
    {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':id', $id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function update($id, $title, $body)
    {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET title = :title, body = :body WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update_title($id, $title)
    {
        // Create query
        $query = 'UPDATE ' . $this->table . ' SET title = :title WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function create($category_id, $title, $body, $author, $created_at)
    {
        // Create query
        $query = 'INSERT INTO ' . $this->table . '(category_id, title, body, author, created_at) VALUES (:category_id, :title, :body, :author, :created_at)';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':created_at', $created_at);

        // Execute query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
