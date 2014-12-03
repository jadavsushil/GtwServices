<?php

class SetupShell extends AppShell {
    public $uses = array('GtwServices.ServiceCategory');
	
    public function main() {
        $this->ServiceCategory->setDefaultData();
        echo "Initial Setup Completed\n";
    }
}