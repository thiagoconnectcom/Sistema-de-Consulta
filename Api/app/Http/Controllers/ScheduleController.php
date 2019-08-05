<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Schedule;
use App\Notification;

class ScheduleController extends Controller
{
    public function index() {
        return response()->json([
            'status'    =>  'success',
            'data'  =>  Schedule::all(),
            'message' => null,
        ], 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'patient'   =>  'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'data'  =>  null,
                'message' => $validator->messages(),
            ], 400);
        }

        DB::beginTransaction();

        try {
            $tarefa = Schedule::create($request->all());
            Notification::create([
                'message' => "O paciente {$tarefa->patient} agendou uma nova colsulta para {$tarefa->date}",
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $tarefa,
                'message' => null,
            ], 201);
        }
        catch(\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id) {
        $tarefa = Schedule::find($id);

        if (!$tarefa) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message'=> 'Tarefa não encontrada',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tarefa,
            'message' => null,
        ]);
    }

    public function update(Request $request, $id) {
        $tarefa = Schedule::find($id);

        if (!$tarefa) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message'=> 'Agendamento não encontrada',
            ], 404);
        }

        DB::beginTransaction();

        try {            
            $tarefa->update([
                'date' => $request->date,
            ]);
            Notification::create([
                'message' => "O paciente {$tarefa->patient} alterou a consulta para {$tarefa->date}",
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => null,
                'message' => 'Agendamento alterado com sucesso',
            ]);
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'erro',
                'data' => null,
                'message' => $e->getMessage(),
            ]);
        }        
    }

    public function destroy($id) {
        $tarefa = Schedule::find($id);

        if (!$tarefa) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message'=> 'Agendamento não encontrada',
            ], 404);
        }

        DB::beginTransaction();

        try {            
            Notification::create([
                'message' => "O paciente {$tarefa->patient} desmarcou a consulta da data {$tarefa->date}",
            ]);

            $tarefa->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => null,
                'message' => 'Agendamento desmarcado com sucesso',
            ]);
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'erro',
                'data' => null,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
