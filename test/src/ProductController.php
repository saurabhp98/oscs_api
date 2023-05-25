<?php
class ProductController {

    public function __construct( private ProductGateway $productGateway){}
    // METHOD FOR PROCESSING REQUEST 
    public function processRequest (string $method, ?string $id):void{
                if ($id ) {
                    $this->requestWithId($method, $id);
                } else {
                    $this->requestWithoutId($method);
                }
    


    }

// process request based on id
    private function requestWithId($method, $id){
switch ($method) {
    case 'GET':
        echo json_encode($this->productGateway->findById($id));
        break;
    case "PATCH" :
        $data = $this->productGateway->findById($id);
        $newdata = (array) json_decode( file_get_contents("php://input"), true);
        $errors = $this->getValidationErrors($newdata);

       if (! empty($errors
       )) {
           http_response_code(422);
           echo json_encode([
               "errors" => $errors
           ]);
           break;
       }

       $id = $this->productGateway->updateProduct($data ,$newdata);
       echo json_encode([
           "message" => "Product updated successfully",
           "id" => $id
       ]);
       break;
    case 'DELETE':
      
      $rows =  $this->productGateway->deleteById($id);
        echo json_encode([
            "message" => "Product $id Deleted successfully",
            "rows" => $rows
        ]);
      
    
}
    }

    // process request based on only method
    private function requestWithoutId($method){
            switch ($method) {
                case 'GET':
                   
                    
                    echo json_encode($this->productGateway->getAll());
                    break;

                case "POST" :
                     $data = (array) json_decode( file_get_contents("php://input"), true);
                     $errors = $this->getValidationErrors($data);

                    if (! empty($errors
                    )) {
                        http_response_code(422);
                        echo json_encode([
                            "errors" => $errors
                        ]);
                        break;
                    }

                    $id = $this->productGateway->create($data);
                    echo json_encode([
                        "message" => "Product Created Successfully",
                        "id" => $id
                    ]);

                    break;
                   
                    default :
                    http_response_code(405);
                    header("Allow: GET, POST");
                
                
            }
    }


    // validating data before saving to database
    private function getValidationErrors($data):array{

        $errors = [];
        if (empty($data["name"])) {
            $errors[] = "Name is required";

        }
        if (array_key_exists("size" , $data)) {
            if (filter_var($data["size"], FILTER_VALIDATE_INT == false)) {
                $errors[] = "Size must be an integer";
            }
        }
        return $errors;
    }
}