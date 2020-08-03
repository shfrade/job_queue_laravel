<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Submitter extends Model
{
    protected $table = 'submitter';
    protected $primaryKey = 'submitter_id';
    public $timestamps = false;

    // public function endereco()
    // {
    //     return $this->hasOne('App\Endereco', 'enderecoId', 'endereco_enderecoId');
    // }

    // public function proprietario()
    // {
    //     return $this->hasOne('App\Usuario', 'usuarioId', 'proprietario_usuarioId');
    // }

    // public function integrador()
    // {
    //     return $this->hasOne('App\Usuario', 'usuarioId', 'uniIntegradorResponsavel');
    // }

//     public function unitList($user, $select = null)
//     {
//         // desabilitando a view, mas deixando como subselect para não criar mais bugs.
//         $results = DB::table(DB::raw("(select
//                 u.unidadeId as consumerUnitId,
//                 e.endCidade as consumerUnitLocationCity,
//                 e.endEstado as consumerUnitLocationRegion,
//                 u.uniNome as consumerUnitName,
//                 ifnull(u.uniStatusUsina,1) as consumerUnitStatusId,
//                 TIMESTAMPDIFF(MINUTE,ho.horarioInicio,NOW()) as consumerUnitStatusTime,
//                 u.uniTipo as consumerUnitType,
//                 c.portal_portalId consumerUnitPortalId,
//                 u.uniInstalacao as consumerUnitInstallDate,
//                 u.uniPotInstalada as consumerUnitSystemSize,
//                 u.uniEnergiaGeradaTotal as consumerUnitTotalGeneratedEnergy,
//                 case
//                     when
//                         ut.unidadesTerceiras_unidadeId is not null
//                         and ifnull((select count(*)
//                             from inversor inv
//                             inner join unidade u2 on u2.unidadeId = inv.inversorUnidadeId
//                             where inv.inversorBoxNumSerie is not null
//                             and  (u2.uniTerceiraSICES = 0 or u2.uniTerceiraSICES is null)
//                             and inv.inversorUnidadeId = u.unidadeId
//                             and inv.inversorAtivo = 1 ),0) = 0
//                     then 1
//                     else 0
//                 end as isDigital,
//                 u.uniCompartilhada as isShared,
//                 u.uniPublica as isPublic,
//                  case
//                     when u.proprietario_usuarioId is not null
//                         then (select temp.usuarioId from usuario temp where temp.usuNivAcesso = 4 and temp.responsavel_integradorId= (select u.responsavel_integradorId from usuario u where usuarioId = u.proprietario_usuarioId) limit 1)
//                     when u.uniIntegradorResponsavel is not null
//                         then (select temp.usuarioId from usuario temp where temp.usuNivAcesso = 4 and temp.responsavel_integradorId= (select u.responsavel_integradorId from usuario u where usuarioId = u.uniIntegradorResponsavel) limit 1)
//                     else null
//                 end as consumerUnitUserDemoId,
//                 p.portalNome as portalName,
//                 u.proprietario_usuarioId as consumerUnitOwnerUserId ,
//                 uo.usuNome as consumerUnitOwnerUsername,
//                 uo.usuEmail as consumerUnitOwnerEmail,
//                 u.uniIntegradorResponsavel as consumerUnitIntegratorUserId,
//                 ui.usuNome as consumerUnitIntegratorUsername,
//                 ui.usuEmail as consumerUnitIntegratorEmail,
//                 case
//                     when pu.unidade_unidadeId is null then 0
//                     when cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) < 70 then 1
//                     when cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) >= 70 and cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) < 90 then 2
//                     when cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) >= 90 then 3
//                     else null
//                 end as consumerUnitStatusSus
//             from unidade u
//             inner join endereco e on e.enderecoId = u.endereco_enderecoId
//             left join historicoOffline ho on ho.unidade_unidadeId = u.unidadeId
//             left join unidadesTerceiras ut on u.unidadeId = ut.unidadesTerceiras_unidadeId
//             left join credencial c on c.credencialId = ut.unidadesTerceiras_credencialId
//             left join portal p on c.portal_portalId = p.portalId
//             left join usuario uo on uo.usuarioId =  u.proprietario_usuarioId
//             left join usuario ui on ui.usuarioId =  u.uniIntegradorResponsavel
//             left join projetoUnidade pu on pu.unidade_unidadeId = u.unidadeId
//             where u.uniAtiva = 1 and u.uniExcluida <> 1) as v_portfolio"));
//         // colunas retornadas
//         $results->select('consumerUnitId', 'consumerUnitLocationCity',
//             'consumerUnitLocationRegion', 'consumerUnitName', 'consumerUnitStatusId',
//             'consumerUnitStatusTime',
//             'consumerUnitType', 'consumerUnitPortalId', 'portal.portalNome as consumerUnitPortalName',
//             'consumerUnitInstallDate',
//             'consumerUnitSystemSize', 'consumerUnitTotalGeneratedEnergy',
//             'isDigital',
//             'isShared', 'isPublic',
//             'consumerUnitUserDemoId',
//             'consumerUnitOwnerUserId',
//             'consumerUnitIntegratorUserId',
//             'consumerUnitIntegratorUsername',
//             'consumerUnitOwnerUsername',
//             'desempenho30dias as consumerUnit30dPerformance',
//             'desempenho12meses as consumerUnit12mPerformance',
//             'desempenhoTotal as consumerUnitTotalPerformance',
//             'portal.portalIconeUrl as consumerUnitPortalIconUrl');
//         $results->selectRaw('case  when ( select count(*) as total from projetoUnidade where unidade_unidadeId = v_portfolio.consumerUnitId) > 0 then 1 else 0 end as performanceData');
//         if ($user->usuNivAcesso != 3) {
//             // filtrar por usuário
//             $results = $results->whereRaw(" ( v_portfolio.consumerUnitOwnerUserId = $user->usuarioId or v_portfolio.consumerUnitIntegratorUserId = $user->usuarioId "
//                 // unidades compartilhadas
//                 . "or v_portfolio.consumerUnitId in ( select unidade_unidadeId from permissao where usuario_usuarioId = $user->usuarioId )"
//                 . ")");
//             $results->selectRaw('(select max(tp.tipoPermissao_tipoPermissaoId)  from permissao tp where tp.usuario_usuarioId = ' . $user->usuarioId . ' and tp.unidade_unidadeId  = v_portfolio.consumerUnitId)  consumerUnitSharedPermission');
//         } else {
//             $results->selectRaw('null as consumerUnitSharedPermission');
//         }

//         if ($select != null) {
//             $results = $results->select($select);
//         }
//         // logos dos portais
//         $results = $results->leftjoin('portal', 'portal.portalId', '=', 'v_portfolio.consumerUnitPortalId');
//         $results = $results->join('unidade', 'unidade.unidadeId', '=', 'v_portfolio.consumerUnitId');
//         return $results;
//     }

//     public function tarifa()
//     {
//         return $this->hasOne('App\UniTarifa', 'unidade_unidadeId', 'unidadeId');
//     }

//     public function classeDaUnidadeString($uniClasse = null)
//     {
//         switch ($uniClasse) {
//             case 0:
//                 return 'Residencial';
//                 break;
//             case 1:
//                 return 'Comercial';
//                 break;
//             case 2:
//                 return 'Industrial';
//                 break;
//             default:
//                 return null;
//                 break;
//         }
//     }

//     public function portfolioInfoSumValues($user)
//     {
//         if ($user->usuNivAcesso == 3) {
//             return DB::select("
//            SELECT
//                 count(*) as totalConsumerUnit,
//                 SUM(uniPotInstalada) AS totalSystemSize,
//                 SUM(uniPotenciaInstantaneaGerada) AS totalGenerationInstantPower,
//                 SUM(uniEnergiaGeradaTotal) AS totalGeneratedEnergy,
//                 SUM(uniEconomiaTotal) AS totalFinantialGenerationEnergy,
//                 SUM(uniSustentabilidadeArvoresTotal) AS totalTrees
//             FROM unidade U
//             WHERE  U.uniExcluida != 1 and U.uniAtiva = 1
//             ;
//         ");
//         }
//         return DB::select("
//             SELECT
//                 SUM(X.totalConsumerUnit) as totalConsumerUnit,
//                 SUM(X.totalSystemSize) AS totalSystemSize,
//                 SUM(X.totalGenerationInstantPower) AS totalGenerationInstantPower,
//                 SUM(X.totalGeneratedEnergy) AS totalGeneratedEnergy,
//                 SUM(X.totalFinantialGenerationEnergy) AS totalFinantialGenerationEnergy,
//                 SUM(X.totalTrees) AS totalTrees
//             FROM
//             (SELECT
//                 count(*) as totalConsumerUnit,
//                 SUM(uniPotInstalada) AS totalSystemSize,
//                 SUM(uniPotenciaInstantaneaGerada) AS totalGenerationInstantPower,
//                 SUM(uniEnergiaGeradaTotal) AS totalGeneratedEnergy,
//                 SUM(uniEconomiaTotal) AS totalFinantialGenerationEnergy,
//                 SUM(uniSustentabilidadeArvoresTotal) AS totalTrees
//             FROM unidade U
//             WHERE (U.proprietario_usuarioid =  $user->usuarioId or U.uniIntegradorResponsavel =  $user->usuarioId)
//                 AND U.uniExcluida != 1
//             UNION
//             SELECT
//                 count(*) as totalConsumerUnit,
//                 SUM(uniPotInstalada) AS totalSystemSize,
//                 SUM(uniPotenciaInstantaneaGerada) AS totalGenerationInstantPower,
//                 SUM(uniEnergiaGeradaTotal) AS totalGeneratedEnergy,
//                 SUM(uniEconomiaTotal) AS totalFinantialGenerationEnergy,
//                 SUM(uniSustentabilidadeArvoresTotal) AS totalTrees
//             FROM unidade U2
//             INNER JOIN permissao P ON P.unidade_unidadeId = U2.unidadeId
//             WHERE P.usuario_usuarioId =  $user->usuarioId
//                 AND U2.uniExcluida != 1
//             ) AS X;
//         ");
//     }

//     public function portfolioUnitsStatus($user)
//     {
//         $sql = "select
//                     SUM(case
//                         when
//                             u.uniStatusUsina in (5,6)
//                         then 1 else 0 end) as noGenerationConsumerUnit,
//                     SUM(case
//                         when
//                             u.uniStatusUsina in (4,10)
//                         then 1 else 0 end) as noCommunicationConsumerUnit,
//                     SUM(case
//                         when
//                             u.uniStatusUsina in (3,13)
//                         then 1 else 0 end) as offlineConsumerUnit,
//                     SUM(case
//                         when
//                             u.uniStatusUsina in (2,11)
//                         then 1 else 0 end) as normalOperationConsumerUnit,
//                     SUM(case
//                         when
//                             u.uniStatusUsina in (1,7,8,9,12) or u.uniStatusUsina is null
//                         then 1 else 0 end) as notMonitoredConsumerUnit,
//                     SUM(case
//                         when
//                             cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) >= 90
//                         then 1 else 0 end) as goodPerformanceUnit,
//                     SUM(case
//                         when
//                             cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) >= 70 and cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) < 90
//                         then 1 else 0 end) as mediumPerformanceUnit,
//                     SUM(case
//                         when
//                             cast(JSON_EXTRACT(u.desempenho12meses, '$[4]') as decimal) < 70
//                         then 1 else 0 end) as badPerformanceUnit,
//                     SUM(case
//                         when
//                            ( select count(*) as total from projetoUnidade where unidade_unidadeId = u.unidadeId) = 0
//                         then 1 else 0 end) as noDataPerformanceUnit
//                 from unidade u
//                 where
//                 (u.uniAtiva = 1 and u.uniExcluida <> 1)";
//         // CASO NÃO SEJA ADMINISTRADOR
//         if ($user->usuNivAcesso != 3) {
//             $sql .= " -- filtro proprietario &  filtro de unidade compartilhada
//                     and ( u.proprietario_usuarioid =  $user->usuarioId or u.uniIntegradorResponsavel =  $user->usuarioId
//                     or
//                      u.unidadeId in (select unidade_unidadeId from permissao where usuario_usuarioId = $user->usuarioId )
//                     )";
//         }
//         return DB::select($sql);
//     }

//     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//     //////////////////////////////////////////////////// DESEMPENHO ////////////////////////////////////////////////////
//     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//     /**
//      * Requisição que retorna para um grupo de unidadaes, o desempenho para
//      * um ano específico. Caso a váriavel de unidade não seja null e o ano
//      * sim, será retornado o histórico de desempenho para a unidade.
//      */
//     public function getPerfomanceUnits($arrayIds = null, $year = null)
//     {
//         // Confere se os parâmetros são válidos.
//         if ($arrayIds != null and $year != null) {
//             // Direciona a query para a tabela de desempenho.
//             $results = DB::table('desempenhoUnidade');
//             // Adiciona as condições para a query.
//             $results = $results->whereIn('unidade_unidadeId', $arrayIds);
//             $results = $results->whereDate('ano', '=', "$year-01-01");
//             $results = $results->get();
//             $results = json_decode(json_encode($results), true);
//             return $results;
//         } else if ($arrayIds != null and $year == null) {
//             // Direciona a query para a tabela de desempenho.
//             $results = DB::table('desempenhoUnidade');
//             // Adiciona as condições para a query.
//             $results = $results->whereIn('unidade_unidadeId', $arrayIds);
//             $results = $results->get();
//             $results = json_decode(json_encode($results), true);
//             return $results;
//         }
//         return null;
//     }

//     /**
//      * Requisição que retorna o maior valor e o menor para a porcentagem do,
//      * desempenho do portfólio de um usuário para determinado ano.
//      */
//     public function getIndexPerformance($userId = null, $year = null)
//     {
//         if ($userId != null && $year != null) {
//             // Foi criada uma view para facilitar os filtros e embolarem menos aqui.
// //            $results = DB::table('v_portfolio');
//             $results = DB::table('unidade');
//             // Se o usuário não for admin, filtra por usuário e traz outras colunas como padrão.
//             if ($userId->usuNivAcesso != 3) {
// //                $results = $results->whereRaw(" ( consumerUnitOwnerUserId = $userId->usuarioId or consumerUnitIntegratorUserId = $userId->usuarioId)");
//                 $results = $results->whereRaw("(unidade.proprietario_usuarioId =  $userId->usuarioId or unidade.uniIntegradorResponsavel =
//                 $userId->usuarioId or unidade.unidadeId in ( select unidade_unidadeId from permissao where usuario_usuarioId =
//                 $userId->usuarioId ) ) ");
//             }            // Colunas retornadas
//             $results = $results->select('unidadeId');
//             $results = $results->get();
//             $results = json_decode($results);
//             // Número de unidades da resposta do select.
//             $numeroUsinas = count($results);
//             for ($i = 0; $i < $numeroUsinas; $i++) {
//                 $arrayIds[] = $results[$i]->unidadeId;
//             }
//             // Seleção do maior e menor desempenho para aquele periodo.
//             $indexResult = DB::table('desempenhoUnidade');
//             // Adiciona as condições do select.
//             $indexResult = $indexResult->whereIn('unidade_unidadeId', $arrayIds);
//             $indexResult = $indexResult->whereDate('ano', '=', "$year-01-01");
//             $indexResult = $indexResult->whereNotNull('maximoDesempenho');
//             $indexResult = $indexResult->whereNotNull('minimoDesempenho');
//             $indexResult = $indexResult->selectRaw("MAX(maximoDesempenho) AS max, MIN(minimoDesempenho) AS min");
//             $indexResult = $indexResult->get();
//             return $indexResult;
//         }
//         return null;
//     }

//     /**
//      * Método que irá fazer o get de dados de projeto para aquela unidade. Caso não tenha o retorno para a unidade
//      * será um array vazio.
//      */
//     public function getProjectData($arrayIds = null)
//     {
//         if ($arrayIds != null) {
//             // Seleciona o banco de dados de projeto
//             $projectData = DB::table('projetoUnidade');
//             // Adiciona as condições do select.
//             $projectData = $projectData->whereIn('unidade_unidadeId', $arrayIds);
//             $projectData = $projectData->get();
//             $projectData = json_decode(json_encode($projectData), true);
//             return $projectData;
//         }
//         return null;
//     }
}
