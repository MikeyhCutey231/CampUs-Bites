<?php
require("../../Admin/functions/dbConfig.php");

class SalesReportData {
    private $conn;

    public function __construct() {
        // Assuming you have a Database class or similar for connecting to the database
        $database = new Connection();
        $this->conn = $database->conn;
    }

    public function getSalesReportFilter($fromDate, $toDate, $selectedCategory, $selectedProduct) {
        $sql = "SELECT *, DATE_FORMAT(TRANSACTION_DATE, '%d-%m-%Y') AS formatted_date FROM salesreport WHERE 1";

        // Log received parameters
        error_log("Received Parameters - fromDate: $fromDate, toDate: $toDate, selectedCategory: $selectedCategory, selectedProduct: $selectedProduct");

        if ($fromDate && $toDate) {
            $sql .= " AND TRANSACTION_DATE BETWEEN ? AND ?";
        }

        if ($selectedCategory !== "") {
            $sql .= " AND CATEGORY_ID = ?";
        }

        if ($selectedProduct !== "") {
            $sql .= " AND PROD_NAME = ?";
        }

        // Log the constructed SQL query
        error_log("Constructed SQL Query: $sql");

        $salesData = array();

        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $paramTypes = "";
            $paramValues = [];

            if ($fromDate && $toDate) {
                $paramTypes .= "ss";
                $paramValues[] = $fromDate;
                $paramValues[] = $toDate;
            }
            if ($selectedCategory) {
                $paramTypes .= "s";
                $paramValues[] = $selectedCategory;
            }
            if ($selectedProduct) {
                $paramTypes .= "s";
                $paramValues[] = $selectedProduct;
            }

            if (!empty($paramTypes) && !empty($paramValues)) {
                $stmt->bind_param($paramTypes, ...$paramValues);
            }

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $row['TRANSACTION_DATE'] = $row['formatted_date']; // Use the formatted date
                    unset($row['formatted_date']); // Remove the extra formatted date column
                    $salesData[] = $row;
                }

                mysqli_free_result($result);

                echo json_encode($salesData);
                error_log(json_encode($salesData)); // Log to PHP error log
            } else {
                // Handle the case when the query fails
                $error = mysqli_error($this->conn);
                echo json_encode(['error' => "Query failed. Error: $error"]);
                error_log("Query failed. Error: $error");
            }
        } else {
            // Handle the case when preparing the statement fails
            $error = $this->conn->error;
            echo json_encode(['error' => "Preparation of statement failed. Error: $error"]);
            error_log("Preparation of statement failed. Error: $error");
        }
    }
}

// Create an instance of the SalesReportData class
$salesReportData = new SalesReportData();

// Call the getSalesReportFilter function with the provided parameters
$salesReportData->getSalesReportFilter($_GET['fromDate'], $_GET['toDate'], $_GET['selectedCategory'], $_GET['selectedProduct']);
?>
