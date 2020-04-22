<?php

namespace Sraban\OnlineVisitor;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Sraban\OnlineVisitor\Models\Employee;
use Sraban\OnlineVisitor\Models\EmployeeWebHistory;

class EmployeeController extends Controller
{
    public $separator = '!@#$%^&*';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$this->storeEmp();
        //$this->storeEmpHistory();
        //$ip_address = '192.168.10.11';
        //return $this->showEmp($ip_address);
        //return $this->showEmpHistory($ip_address);
        //$this->destroyEmp($ip_address);
        //$this->destroyEmpHistory($ip_address);
        return view('online-visitor::app');
    }

    function payLoad($contentArr = []) {

        if(empty($contentArr)) {
            $content = file_get_contents('php://input');
            $contentArr = @explode(PHP_EOL, $content);
        }
        
        $flag = true;
        $contentArr = array_filter($contentArr, function($element) use( &$flag ) {
            $element = trim($element);
            if( strtolower($element) == "end" && $flag == true ) $flag = false;
            if( $element && $flag == true ){
                return $element;
            }            
        });

        if($flag == true) {
            return ;
        }

        /* -For "- */
        foreach($contentArr as $k => &$element)
        {
            $doubleQuote = preg_match_all('/\"(.*?)\"/', $element, $m);
            if(!empty($m) && count($m[0]) !== 0 && $doubleQuote > 0 ) {
                foreach($m[0] as $string) {
                    $replace = str_replace(' ',$this->separator, $string);
                    $replace = str_replace('"','', $replace);
                    $element = str_replace($string, $replace, $element);
                }
            }
        }

        /* -For '- */
        foreach($contentArr as $k => &$element)
        {
            $singleQuote = preg_match_all("/\'(.*?)\'/", $element, $m);
            if(!empty($m) && count($m[0]) !== 0 && $singleQuote > 0) {
                foreach($m[0] as $string) {
                    $replace = str_replace(' ',$this->separator, $string);
                    $replace = str_replace("'",'', $replace);
                    $element = str_replace($string, $replace, $element);
                }
            }
        }

        /* -For ‘’- */
        foreach($contentArr as $k => &$element)
        {
            $backQuote = preg_match_all("/\‘(.*?)\’/", $element, $m);
            if(!empty($m) && count($m[0]) !== 0 && $backQuote > 0) {
                foreach($m[0] as $string) {
                    $replace = str_replace(' ',$this->separator, $string);
                    $replace = str_replace('‘','', $replace);
                    $replace = str_replace('’','', $replace);
                    $element = str_replace($string, $replace, $element);
                }
            }
        }

        /* -For `- */
        foreach($contentArr as $k => &$element)
        {
            $backQuote = preg_match_all("/\`(.*?)\`/", $element, $m);
            if(!empty($m) && count($m[0]) !== 0 && $backQuote > 0) {
                foreach($m[0] as $string) {
                    $replace = str_replace(' ',$this->separator, $string);
                    $replace = str_replace('`','', $replace);
                    $element = str_replace($string, $replace, $element);
                }
            }
        }


        /* -To remove multiple white spaces- */
        foreach($contentArr as $k => &$element)
        {
            $backQuote = @explode(" ", $element);
            $backQuote = array_filter($backQuote, function($e) {
                if($e) return true;
            });
            $element = @implode(" ", $backQuote);
        }
        return $contentArr;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function statement(Array $inputArray = [])
    {
        $output = [];
        $contentArr = $this->payLoad( $inputArray );

        if($contentArr) {
            foreach($contentArr as $query) {
                $method = strtolower(substr($query,0,3));
                
                if($method == "get" ) {
                    list($m, $table, $ip_address) = @explode(" ", $query);
                    $ip_address = str_replace($this->separator, ' ',$ip_address);
                    // query
                    if(strtolower($table) == 'empwebhistory'){
                        array_push($output, $this->showEmpHistory($ip_address));
                        Log::info('get - empwebhistory - '.$ip_address);
                    } else if(strtolower($table) == 'empdata') {
                        array_push($output, $this->showEmp($ip_address));
                        Log::info('get - empdata - '.$ip_address);
                    }

                }

                if( strtolower(substr($query,0,5)) == "unset" ) {
                    list($m, $table, $ip_address) = @explode(" ", $query);
                    $ip_address = str_replace($this->separator, ' ',$ip_address);
                    // query
                    if(strtolower($table) == 'empwebhistory'){
                        array_push($output, $this->destroyEmpHistory($ip_address));
                        Log::info('del - empwebhistory - '.$ip_address);
                    } else if(strtolower($table) == 'empdata') {
                        array_push($output, $this->destroyEmp($ip_address));
                        Log::info('del - empdata - '.$ip_address);
                    }
                }

                if($method == "set" ) {

                    $q = @explode(" ", $query);
                    if(strtolower($q[1]) == 'empwebhistory'){
                        $ip_address = str_replace($this->separator, ' ', @$q[2]);
                        $url = str_replace($this->separator, ' ', @$q[3]);
                        $input = ['url'=>$url, 'ip_address' => $ip_address];
                        array_push($output, $this->storeEmpHistory($input));
                        Log::info('set - empwebhistory - ', $input);
                    } else if(strtolower($q[1]) == 'empdata') {
                        $emp_id = str_replace($this->separator, ' ', @$q[2]);
                        $emp_name = str_replace($this->separator, ' ', @$q[3]);
                        $ip_address = str_replace($this->separator, ' ', @$q[4]);
                        $input = ['emp_id' => $emp_id, 'emp_name' => $emp_name, 'ip_address' => $ip_address ];
                        array_push($output, $this->storeEmp($input));
                        Log::info('set - empdata - ', $input);
                    }

                }
            }

        }

        return $output;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeEmp($input)
    {
        $validator = Validator::make($input, [
            'emp_id' => 'required|unique:employees|max:255',
            'emp_name' => 'required|max:255',
            'ip_address' => 'required|unique:employees|max:45'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if(Employee::create($input)){
            return 'Saved';
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeEmpHistory($input)
    {
            $validator = Validator::make($input, [
                'url' => 'required|max:255|unique:employee_web_histories,url,NULL,id,ip_address,' . $input['ip_address'],
                'ip_address' => 'required|exists:employees,ip_address|max:45|unique:employee_web_histories,ip_address,NULL,id,url,' . $input['url']
            ]);

            if ($validator->fails()) {
                return $validator->errors();
            }

            if(EmployeeWebHistory::create($input)){
                return 'Saved';
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function showEmp($ip_address)
    {
        $e = Employee::where('ip_address',$ip_address)->first();
        if( $e && $e->id ) {
            return [
                "id"  => $e->id,
                "empId" => $e->emp_id,
                "empName" => $e->emp_name,
                "empIpAddress" => $e->ip_address
            ];
        } else {
            return 'Resource not found';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmployeeWebHistory  $employee
     * @return \Illuminate\Http\Response
     */
    public function showEmpHistory($ip_address)
    {
        $eh = Employee::with('history')->where('ip_address',$ip_address)->first();
        if($eh && $eh->emp_id && $eh->history->count() ) {
            
            $urls = array();

            if($eh->history){
                foreach($eh->history as $eh_row){
                    array_push($urls, ['url' => $eh_row->url]);
                }
            }

            return [
                "empid" => $eh->emp_id,
                "empIpAddress" => $eh->ip_address,   
                "urls" => $urls
            ];

        } else {
            return 'Resource not found';
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroyEmp($ip_address)
    {
        $emp = Employee::where('ip_address', $ip_address);
        if( $emp && $emp->count() ){
            if( $emp->delete() ) {
                return 'Deleted';
            }
        } else {
            return 'NULL';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroyEmpHistory($ip_address)
    {
        $empHistory = EmployeeWebHistory::where('ip_address', $ip_address);
        if( $empHistory && $empHistory->count() ) {
            if( $empHistory->delete() ) {
                return 'Deleted';
            }
        } else {
            return 'NULL';
        }    
    }

    public function refreshEmployee() {
        $seed = new Employee();
        $seed->clean();
    }
}
