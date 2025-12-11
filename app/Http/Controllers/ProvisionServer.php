<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProvisionServer extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $serverName = $request->input('server_name');

        return redirect('/provision-server')->with('message', "Server '{$serverName}' has been provisioned successfully.");
    }
}
 