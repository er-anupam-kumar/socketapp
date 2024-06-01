<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Events\CameraDataUpdated;
use Carbon\Carbon;

class SocketServer extends Command
{
    protected $signature = 'socket:serve';
    protected $description = 'Start a socket server to receive data from the camera';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $host = '127.0.0.1'; // Localhost
        $port = 8080;        // The port your camera sends data to

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            $this->error("socket_create() failed: " . socket_strerror(socket_last_error()));
            return;
        }

        $result = socket_bind($socket, $host, $port);
        if ($result === false) {
            $this->error("socket_bind() failed: " . socket_strerror(socket_last_error($socket)));
            return;
        }

        $result = socket_listen($socket, 5);
        if ($result === false) {
            $this->error("socket_listen() failed: " . socket_strerror(socket_last_error($socket)));
            return;
        }

        $this->info("Server listening on $host:$port");

        while (true) {
            $client = socket_accept($socket);
            if ($client === false) {
                $this->error("socket_accept() failed: " . socket_strerror(socket_last_error($socket)));
                continue;
            }

            $input = socket_read($client, 1024);
            $this->info("Received: $input");

            // Save data to the database
            $data = [
                'data' => $input,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            DB::table('camera_data')->insert($data);

            $output = "Data received";
            socket_write($client, $output, strlen($output));

            socket_close($client);
        }

        socket_close($socket);
    }
}
