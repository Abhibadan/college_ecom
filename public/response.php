<?php
class Response{
    public function success($message,$data = null){
        echo json_encode([
            'status' => '200',
            'message' => $message,
            'data' => $data
           
        ]);
    }

    public function error($message){
        // header("HTTP/1.0 500 Internal Server Error");
        $data = [
            'status' => '403',
            'message' => $message
        ];
        echo json_encode($data);
        die();
    }
}
?>