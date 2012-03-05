<?php 

class UbiGeo
{
    public function __construct() {
        mysql_connect('localhost', 'usuario', 'clave');
        mysql_select_db('test');
    }

    public function recibir($origen, $filtro)
    {
        switch ($origen) {
            case 'dpto':
                $sql = "SELECT id, desdep AS descri FROM departamentos";
                break;
            case 'prov':
                $sql = "SELECT id, despro AS descri FROM provincias
                        WHERE departamento_id = $filtro";
                break;
            case 'dist':
                $sql = "SELECT id, desdist AS descri FROM distritos
                        WHERE provincia_id = $filtro";
                break;
            case 'cent':
                $sql = "SELECT ce.id, CONCAT(descat, ' ', descen) AS descri 
                        FROM centros ce
                        INNER JOIN categorias ca
                        ON ce.categoria_id = ca.id
                        WHERE distrito_id = $filtro";
                break;
        }
        $this->generarArreglos($sql);
    }

    private function generarArreglos($sql)
    {
        $data = array();

        $rs = mysql_query($sql);

        while($reg = mysql_fetch_assoc($rs)) {
            $reg['descri'] = utf8_encode($reg['descri']);
            $data[] = $reg;
        }
        echo json_encode($data);
    }
}

extract($_REQUEST);

$obUbigeo = new UbiGeo();

$obUbigeo->recibir($accion, $id);