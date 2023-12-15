<?php
include_once '../../Admin/functions/dbConfig.php';


class Notification extends Connection {

    function countNotification($customer_id){
        $totals = 0;
        $sql = "SELECT COUNT(*) as 'TOTAL_NOTIF'
        FROM notifications
        INNER JOIN online_order ON notifications.ol_order_id = online_order.online_order_id
        INNER JOIN ol_cart ON online_order.ol_cart_id = ol_cart.ol_cart_id
        WHERE ol_cart.customer_id = $customer_id and notifications.STATUS='unread';";

        $result = $this->conn->query($sql);

        if($result){
            while($row = $result->fetch_assoc()){
                $totals = $row['TOTAL_NOTIF'];
                 
            }
        }
        else{
            die('Error executing the query: ' . $this->conn->error);
        }

        return $totals;
    }

   function markNotificationsAsRead($customer_id){
        // Assuming you have a method to get the unread notifications
        $unreadNotifications = $this->getUnreadNotifications($customer_id);

        // Assuming you have a method to update the notification status
        foreach ($unreadNotifications as $notification) {
            $notificationId = $notification['notification_id'];
            $this->updateNotificationStatus($notificationId);
        }
    }

    private function getUnreadNotifications($customer_id){
        $sql = "SELECT notification_id FROM notifications
        INNER JOIN online_order ON notifications.ol_order_id = online_order.online_order_id
        INNER JOIN ol_cart ON online_order.ol_cart_id = ol_cart.ol_cart_id
        WHERE ol_cart.customer_id = ? and notifications.STATUS='unread'";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $notifications;
    }

    private function updateNotificationStatus($notificationId){
        $sql = "UPDATE notifications SET STATUS = 'read' WHERE NOTIFICATION_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $notificationId);
        $stmt->execute();
        $stmt->close();
    }

    public function getNotificationsForCustomer($customer_id){
        $notifications = array();

        $sql = "SELECT notifications.NOTIFICATION_ID,
            notifications.NOTIF_MESSAGE,
            notifications.STATUS,
            ol_order_status.STATUS_NAME,
            online_order.ONLINE_ORDER_ID,
            online_order.OL_ORDER_TYPE_ID
        
           FROM notifications
            INNER JOIN online_order ON notifications.ol_order_id = online_order.online_order_id
            INNER JOIN ol_cart ON online_order.ol_cart_id = ol_cart.ol_cart_id
            INNER JOIN ol_order_status on online_order.ORDER_STATUS_ID = ol_order_status.ORDER_STATUS_ID
            WHERE ol_cart.customer_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }

        $stmt->close();

        return $notifications;
    }
}






