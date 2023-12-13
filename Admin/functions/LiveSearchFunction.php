<?php

// OOP sa MAINSEARCH
class SearchHandler {
    public function performSearch($searchTerm) {
        $lowercaseSearchTerm = strtolower($searchTerm);

        if ($lowercaseSearchTerm === 'dashboard') {

            header('Location: admin-dashboard.php');
            exit;
        } else if ($lowercaseSearchTerm === 'cashier') {

            header('Location: admin-cashier.php');
            exit;
        }
        else if ($lowercaseSearchTerm === 'cook') {

            header('Location: admin-cook.php');
            exit;
        }
        else if ($lowercaseSearchTerm === 'assistant cook' || $lowercaseSearchTerm === 'asst cook' || $lowercaseSearchTerm === 'assistantcook' || $lowercaseSearchTerm === 'asstcook' || $lowercaseSearchTerm === 'assistant') {
            header('Location: admin-asstcook.php');
            exit;
        }
        else if ($lowercaseSearchTerm === 'manager') {

            header('Location: admin-manager.php');
            exit;
        }
        else if ($lowercaseSearchTerm === 'server') {

            header('Location: admin-server.php');
            exit;
        }else if ($lowercaseSearchTerm === 'courier') {

            header('Location: admin-courier.php');
            exit;
        }else if ($lowercaseSearchTerm === 'customers' || $lowercaseSearchTerm === 'customer') {

            header('Location: admin-Customer.php');
            exit;
        }else if ($lowercaseSearchTerm === 'item inventory' || $lowercaseSearchTerm === 'item' || $lowercaseSearchTerm === 'inventory') {

            header('Location: admin_itemInventory.php');
            exit;
        }else if ($lowercaseSearchTerm === 'payroll') {

            header('Location: admin-payroll.php');
            exit;
        }else if ($lowercaseSearchTerm === 'reports') {

            header('Location: admin-reports.php');
            exit;
        }
        else if ($lowercaseSearchTerm === 'sign out' || $lowercaseSearchTerm === 'logout') {

            header('Location: admin-logout.php');
            exit;
        }

    }
}

// OOP SA SORT
class TableSorter
{
    private $tableSelector;
    private $sortDropdownSelector;

    public function __construct($tableSelector, $sortDropdownSelector)
    {
        $this->tableSelector = $tableSelector;
        $this->sortDropdownSelector = $sortDropdownSelector;
    }

    public function addSortingScript()
    {
        echo '<script>
            $(document).ready(function() {
                $("' . $this->sortDropdownSelector . '").on("change", function() {
                    let selectedValue = $(this).val();
                    let rows = $("' . $this->tableSelector . ' tbody tr").get();

                    rows.sort(function(a, b) {
                        let aData = $(a).children("td").eq(1).text(); // Last Name
                        let bData = $(b).children("td").eq(1).text();

                        if (selectedValue === "1") {
                            return aData.localeCompare(bData); // A to Z
                        } else if (selectedValue === "2") {
                            return bData.localeCompare(aData); // Z to A
                        }
                        // Add more sorting cases if needed

                        return 0;
                    });

                    $("' . $this->tableSelector . ' tbody").empty().append(rows);
                });
            });
        </script>';
    }
}






?>