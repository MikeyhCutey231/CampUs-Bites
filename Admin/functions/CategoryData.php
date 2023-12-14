<?php
require("../../Admin/functions/dbConfig.php");

class CategoryData {
    private $conn;

    public function __construct() {
        $database = new Connection();
        $this->conn = $database->conn;
    }

    public function getCategories() {
        $sql = "SELECT category_id, category_name FROM prod_category";
        $categories = array();

        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $category = array(
                    'id' => $row["category_id"],
                    'name' => $row["category_name"],
                );

                $categories[] = $category;
            }

            mysqli_free_result($result);
        }

        return json_encode($categories);
    }
}

// Create an instance of CategoryData
$categoryData = new CategoryData();

// Output JSON response with categories
header('Content-Type: application/json');
echo $categoryData->getCategories();
?>
