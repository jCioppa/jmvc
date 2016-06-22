Application : a command line program
Command : a command you can issue. 
Argument : an argument you can issue to a command

for example

    Application : php 
            Command : artisan 
                    options  : [-v,-V,...], 
                    Arguments : [

                        [
                        name : make:controller 
                            value : MyController,
                            options = [-plain, -migration],
                        ],

                        [
                        name : make:migration,
                            value : MyMigration,
                            options : [....]
                        ]
                    ]


