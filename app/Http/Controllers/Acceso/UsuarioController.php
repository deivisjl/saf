<?php

namespace App\Http\Controllers\Acceso;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accesos.usuarios.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accesos.usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {
            $rules = [
                'nombres'=>'required',
                'apellidos'=>'required',
                'email'=>'required|unique:users',
                'password'=>'required|string|min:5|confirmed',
            ];

            $this->validate($request,$rules);

            $registro = new User();
            $registro->nombres = $request->get('nombres');
            $registro->apellidos = $request->get('apellidos');
            $registro->email = $request->get('email');
            $registro->password = bcrypt($request->get('password'));
            $registro->save();

            return response()->json(['data' => 'Registro generado con éxito']);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("id","nombres","apellidos","email");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $usuarios = DB::table('users') 
                ->select('id','nombres','apellidos','email') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('users')                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $usuarios,
            );
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = User::findOrFail($id);

        return view('accesos.usuarios.edit',['registro' => $registro]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try 
        {
            $rules = [
                'nombres'=>'required',
                'apellidos'=>'required',
                'email'=>'required|unique:users,email,'.$id,
            ];

            $this->validate($request,$rules);

            $registro = User::findOrFail($id);
            $registro->nombres = $request->get('nombres');
            $registro->apellidos = $request->get('apellidos');
            $registro->email = $request->get('email');
            $registro->save();

            return response()->json(['data' => 'Registro actualizado con éxito']);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $usuario = User::findOrFail($id);
            if(Auth::user()->id == $usuario->id)
            {
                throw new \Exception("No se puede eliminar al usuario que está en sesión", 1);
            }

            $usuario->delete();

            return response()->json(['data' => 'Registro eliminado con éxito'],200);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }
    public function modificarAcceso()
    {
        return view('accesos.usuarios.credencial');
    }
    public function actualizarAcceso(Request $request)
    {
        try 
        {
            $rules = [
                'password'=>'required|string|min:5|confirmed',
            ];

            $this->validate($request,$rules);

            $registro = Auth::user();
            $registro->password = bcrypt($request->get('password'));
            $registro->save();

            return response()->json(['data' => 'Registro actualizado con éxito']);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }
}
