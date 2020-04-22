<?php

namespace Sraban\OnlineVisitor\Commands;

use Illuminate\Console\Command;
use Sraban\OnlineVisitor\EmployeeController;

class OnlineVisitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ov';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Online Visitor Dashboard';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $this->info("Enter the statements: SET,GET, UNSET");

        $queries = [];
        $i = 0;
        do {
            $query = $this->ask($i+1);
            array_push($queries,$query);
            $i++;
        } while( strtolower( trim($query) ) != "end" );


        if(!empty($queries)){
            $controller = new EmployeeController();
            $ouput = $controller->update($queries);
            $this->info( json_encode($ouput) );
        } else{
            $this->error('No Input');
        }

    }
}
