<?php

namespace App\Http\Controllers\Adminpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use DataTables;

class DatabaseBackupController extends Controller
{
    function __construct()
    {

        $this->middleware('permission:province-list|province-create|province-edit|province-delete', ['only' => ['list']]);
        $this->middleware('permission:province-create', ['only' => ['index', 'store']]);
        $this->middleware('permission:province-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:province-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('adminpanel.databasebackup.index');
    }

    public function our_backup_database(){

        //ENTER THE RELEVANT INFO BELOW
        $mysqlHostName      = env('DB_HOST');
        $mysqlUserName      = env('DB_USERNAME');
        $mysqlPassword      = env('DB_PASSWORD');
        $DbName             = env('DB_DATABASE');
        $backup_name        = "mybackup.sql";
        $tables             = array("business_natures","calendars","cities","complaint_category_details","complaint_documents","complaint_histories","complaint_registration",
                                    "complaint_remarks","complaint_status","complaint_status_types","complain_categories","districts","dynamic_menus","dynamic_menus_copy",
                                    "establishment_types","events","failed_jobs","forward_complaint_details","forward_types","grama_niladhari","gratuity_details",
                                    "labour_offices_divisions","labour_office_city_details","log_activities","mail_histories","mail_templates","migrations",
                                    "minimum_wages_main","minimum_wage_details","model_has_permissions","model_has_roles","multiple_sms_email_record","office_types",
                                    "password_resets","permissions","personal_access_tokens","privilage","provinces","register_complaints","register_complaint_copies",
                                    "roles","role_has_permissions","sms_logs","sms_templates","union_officer_details","users"); //here your tables...

        $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();


        $output = '';
        foreach($tables as $table)
        {
         $show_table_query = "SHOW CREATE TABLE " . $table . "";
         $statement = $connect->prepare($show_table_query);
         $statement->execute();
         $show_table_result = $statement->fetchAll();

         foreach($show_table_result as $show_table_row)
         {
          $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
         }
         $select_query = "SELECT * FROM " . $table . "";
         $statement = $connect->prepare($select_query);
         $statement->execute();
         $total_row = $statement->rowCount();

         for($count=0; $count<$total_row; $count++)
         {
          $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
          $table_column_array = array_keys($single_result);
          $table_value_array = array_values($single_result);
          $output .= "\nINSERT INTO $table (";
          $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
          $output .= "'" . implode("','", $table_value_array) . "');\n";
         }
        }
        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
           header('Pragma: public');
           header('Content-Length: ' . filesize($file_name));
           ob_clean();
           flush();
           readfile($file_name);
           unlink($file_name);

           return view('adminpanel.databasebackup.index');


    }
}

