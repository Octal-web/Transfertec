<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\MarcasController;

use App\Http\Controllers\Engenharia\HomeController as EngenhariaHomeController;
use App\Http\Controllers\Engenharia\CasesController as EngenhariaCasesController;
use App\Http\Controllers\Engenharia\InstitucionalController as EngenhariaInstitucionalController;
use App\Http\Controllers\Engenharia\ProdutosController as EngenhariaProdutosController;
use App\Http\Controllers\Engenharia\ServicosController as EngenhariaServicosController;
use App\Http\Controllers\Engenharia\ContatoController as EngenhariaContatoController;
use App\Http\Controllers\Engenharia\NewsController as EngenhariaNewsController;
use App\Http\Controllers\Engenharia\PoliticasController as EngenhariaPoliticasController;

use App\Http\Controllers\Engenharia\Manager\UsuariosController as EngenhariaUsuariosController;
use App\Http\Controllers\Engenharia\Manager\PaginasController as EngenhariaManagerPaginasController;
use App\Http\Controllers\Engenharia\Manager\ConteudosController as EngenhariaManagerConteudosController;
use App\Http\Controllers\Engenharia\Manager\ImagensController as EngenhariaManagerImagensController;
use App\Http\Controllers\Engenharia\Manager\FinderController as EngenhariaManagerFinderController;
use App\Http\Controllers\Engenharia\Manager\HomeController as EngenhariaManagerHomeController;
use App\Http\Controllers\Engenharia\Manager\SlidesController as EngenhariaManagerSlidesController;
use App\Http\Controllers\Engenharia\Manager\SetoresController as EngenhariaManagerSetoresController;
use App\Http\Controllers\Engenharia\Manager\CasesController as EngenhariaManagerCasesController;
use App\Http\Controllers\Engenharia\Manager\BlocosCasesController as EngenhariaManagerBlocosCasesController;
use App\Http\Controllers\Engenharia\Manager\ImagensCasesController as EngenhariaManagerImagensCasesController;
use App\Http\Controllers\Engenharia\Manager\DepoimentosController as EngenhariaManagerDepoimentosController;
use App\Http\Controllers\Engenharia\Manager\ClientesController as EngenhariaManagerClientesController;
use App\Http\Controllers\Engenharia\Manager\InstitucionalController as EngenhariaManagerInstitucionalController;
use App\Http\Controllers\Engenharia\Manager\MembrosController as EngenhariaManagerMembrosController;
use App\Http\Controllers\Engenharia\Manager\CertificacoesController as EngenhariaManagerCertificacoesController;
use App\Http\Controllers\Engenharia\Manager\ServicosController as EngenhariaManagerServicosController;
use App\Http\Controllers\Engenharia\Manager\EtapasController as EngenhariaManagerEtapasController;
use App\Http\Controllers\Engenharia\Manager\ProdutosController as EngenhariaManagerProdutosController;
use App\Http\Controllers\Engenharia\Manager\ContatoController as EngenhariaManagerContatoController;
use App\Http\Controllers\Engenharia\Manager\OrcamentosController as EngenhariaManagerOrcamentosController;
use App\Http\Controllers\Engenharia\Manager\NewsController as EngenhariaManagerNewsController;
use App\Http\Controllers\Engenharia\Manager\PostsController as EngenhariaManagerPostsController;
use App\Http\Controllers\Engenharia\Manager\PostsCategoriasController as EngenhariaManagerPostsCategoriasController;
use App\Http\Controllers\Engenharia\Manager\PoliticasController as EngenhariaManagerPoliticasController;

use App\Http\Controllers\Enologia\HomeController as EnologiaHomeController;
use App\Http\Controllers\Enologia\InstitucionalController as EnologiaInstitucionalController;
use App\Http\Controllers\Enologia\ProjetosController as EnologiaProjetosController;
use App\Http\Controllers\Enologia\InsumosController as EnologiaInsumosController;
use App\Http\Controllers\Enologia\AutomacaoController as EnologiaAutomacaoController;
use App\Http\Controllers\Enologia\EquipamentosController as EnologiaEquipamentosController;
use App\Http\Controllers\Enologia\ConsultoriaController as EnologiaConsultoriaController;
use App\Http\Controllers\Enologia\ContatoController as EnologiaContatoController;
use App\Http\Controllers\Enologia\NewsController as EnologiaNewsController;
use App\Http\Controllers\Enologia\PoliticasController as EnologiaPoliticasController;

use App\Http\Controllers\Enologia\Manager\UsuariosController as EnologiaUsuariosController;
use App\Http\Controllers\Enologia\Manager\PaginasController as EnologiaManagerPaginasController;
use App\Http\Controllers\Enologia\Manager\ConteudosController as EnologiaManagerConteudosController;
use App\Http\Controllers\Enologia\Manager\ImagensController as EnologiaManagerImagensController;
use App\Http\Controllers\Enologia\Manager\FinderController as EnologiaManagerFinderController;
use App\Http\Controllers\Enologia\Manager\HomeController as EnologiaManagerHomeController;
use App\Http\Controllers\Enologia\Manager\SlidesController as EnologiaManagerSlidesController;
use App\Http\Controllers\Enologia\Manager\SolucoesController as EnologiaManagerSolucoesController;
use App\Http\Controllers\Enologia\Manager\ParceirosController as EnologiaManagerParceirosController;
use App\Http\Controllers\Enologia\Manager\InstitucionalController as EnologiaManagerInstitucionalController;
use App\Http\Controllers\Enologia\Manager\AcontecimentosController as EnologiaManagerAcontecimentosController;
use App\Http\Controllers\Enologia\Manager\ProjetosController as EnologiaManagerProjetosController;
use App\Http\Controllers\Enologia\Manager\EtapasController as EnologiaManagerEtapasController;
use App\Http\Controllers\Enologia\Manager\InsumosController as EnologiaManagerInsumosController;
use App\Http\Controllers\Enologia\Manager\InsumosCategoriasController as EnologiaManagerInsumosCategoriasController;
use App\Http\Controllers\Enologia\Manager\InsumosSubcategoriasController as EnologiaManagerInsumosSubcategoriasController;
use App\Http\Controllers\Enologia\Manager\MarcasController as EnologiaManagerMarcasController;
use App\Http\Controllers\Enologia\Manager\InsumosProdutosController as EnologiaManagerInsumosProdutosController;
use App\Http\Controllers\Enologia\Manager\AutomacaoController as EnologiaManagerAutomacaoController;
use App\Http\Controllers\Enologia\Manager\ProcessosController as EngenhariaManagerProcessosController;
use App\Http\Controllers\Enologia\Manager\ImagensProcessosController as EnologiamaNagerimAgensprOcessoscoNtroller;
use App\Http\Controllers\Enologia\Manager\EquipamentosController as EnologiaManagerEquipamentosController;
use App\Http\Controllers\Enologia\Manager\EquipamentosCategoriasController as EnologiaManagerEquipamentosCategoriasController;
use App\Http\Controllers\Enologia\Manager\EquipamentosSubcategoriasController as EnologiaManagerEquipamentosSubcategoriasController;
use App\Http\Controllers\Enologia\Manager\EquipamentosProdutosController as EnologiaManagerEquipamentosProdutosController;
use App\Http\Controllers\Enologia\Manager\NewsController as EnologiaManagerNewsController;
use App\Http\Controllers\Enologia\Manager\PostsController as EnologiaManagerPostsController;
use App\Http\Controllers\Enologia\Manager\PostsCategoriasController as EnologiaManagerPostsCategoriasController;
use App\Http\Controllers\Enologia\Manager\PoliticasController as EnologiaManagerPoliticasController;

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::get('/', [MarcasController::class, 'index'])->name('Marcas.index');

    Route::prefix('/engenharia')->group(function() {
        Route::get('/', [EngenhariaHomeController::class, 'index'])->name('Engenharia.Home.index');

        Route::get('/cases', [EngenhariaCasesController::class, 'index'])->name('Engenharia.Cases.index');
        Route::get('/cases/{slug}', [EngenhariaCasesController::class, 'caseCliente'])->name('Engenharia.Cases.caseCliente');

        Route::get('/sobre-nos', [EngenhariaInstitucionalController::class, 'index'])->name('Engenharia.Institucional.index');

        Route::get('/setores-de-atuacao', [EngenhariaProdutosController::class, 'index'])->name('Engenharia.Produtos.index');

        Route::get('/contato', [EngenhariaContatoController::class, 'index'])->name('Engenharia.Contato.index');
        Route::post('/contato/enviar', [EngenhariaContatoController::class, 'enviar'])->name('Engenharia.Contato.enviar');

        // Route::get('/solicitar-orcamento', [EngenhariaOrcamentosController::class, 'index'])->name('Engenharia.Orcamentos.index');
        // Route::post('/solicitar-orcamento/enviar', [EngenhariaOrcamentosController::class, 'enviar'])->name('Engenharia.Orcamentos.enviar');

        Route::get('/servicos', [EngenhariaServicosController::class, 'index'])->name('Engenharia.Servicos.index');

        Route::get('/news', [EngenhariaNewsController::class, 'index'])->name('Engenharia.News.index');
        Route::get('/news/{categoria}/{slug}', [EngenhariaNewsController::class, 'post'])->name('Engenharia.News.post');

        Route::get('/politica-de-privacidade', [EngenhariaPoliticasController::class, 'privacidade'])->name('Engenharia.Politicas.privacidade');
    });

    Route::prefix('/enologia')->group(function() {
        Route::get('/', [EnologiaHomeController::class, 'index'])->name('Enologia.Home.index');

        Route::get('/sobre-nos', [EnologiaInstitucionalController::class, 'index'])->name('Enologia.Institucional.index');

        Route::get('/projetos-de-vinicolas', [EnologiaProjetosController::class, 'index'])->name('Enologia.Projetos.index');

        Route::get('/insumos-e-produtos', [EnologiaInsumosController::class, 'index'])->name('Enologia.Insumos.index');
        Route::get('/insumos-e-produtos/download/{tipo}/{id}', [EnologiaInsumosController::class, 'download'])->name('Enologia.Insumos.download');

        Route::get('/automacao-de-processos', [EnologiaAutomacaoController::class, 'index'])->name('Enologia.Automacao.index');
        
        Route::get('/consultoria', [EnologiaConsultoriaController::class, 'index'])->name('Enologia.Consultoria.index');

        Route::get('/equipamentos', [EnologiaEquipamentosController::class, 'index'])->name('Enologia.Equipamentos.index');
        Route::get('/equipamentos/download/{tipo}/{id}', [EnologiaEquipamentosController::class, 'download'])->name('Enologia.Equipamentos.download');

        Route::get('/contato', [EnologiaContatoController::class, 'index'])->name('Enologia.Contato.index');
        Route::post('/contato/enviar', [EnologiaContatoController::class, 'enviar'])->name('Enologia.Contato.enviar');

        Route::get('/news', [EnologiaNewsController::class, 'index'])->name('Enologia.News.index');
        Route::get('/news/{categoria}/{slug}', [EnologiaNewsController::class, 'post'])->name('Enologia.News.post');

        Route::get('/politica-de-privacidade', [EnologiaPoliticasController::class, 'privacidade'])->name('Enologia.Politicas.privacidade');
    });
});

Route::prefix('/engenharia')->group(function() {
    Route::prefix('/manager')->group(function() {
        Route::get('/', [EngenhariaUsuariosController::class, 'login'])->name('Engenharia.Manager.Usuarios.login');
        Route::post('/', ['as' => 'engenharia.login', 'uses' => 'App\Http\Controllers\Engenharia\Manager\UsuariosController@authenticate']);

        Route::post('/usuarios/logout', [EngenhariaUsuariosController::class, 'logout'])->name('Engenharia.Manager.Usuarios.logout');

        Route::group(['middleware' => ['auth:engenharia']], function() {
            Route::post('/paginas/editar/{id}', [EngenhariaManagerPaginasController::class, 'editarAction'])->name('Engenharia.Manager.Paginas.editar');

            Route::post('/conteudos/editar/{id}', [EngenhariaManagerConteudosController::class, 'editarAction'])->name('Engenharia.Manager.Conteudos.editar');
            Route::post('/conteudos/baixar-arquivo/{id}', [EngenhariaManagerConteudosController::class, 'baixarArquivo'])->name('Engenharia.Manager.Conteudos.baixarArquivo');

            Route::get('/imagens/{id}', [EngenhariaManagerImagensController::class, 'conteudo'])->name('Engenharia.Manager.Imagens.conteudo');
            Route::post('/imagens/conteudo/adicionar/{id}', [EngenhariaManagerImagensController::class, 'novo'])->name('Engenharia.Manager.Imagens.novo');
            
            Route::post('/imagens/conteudo/ordenar/{id}', [EngenhariaManagerImagensController::class, 'ordenar'])->name('Engenharia.Manager.Imagens.ordenar');
            Route::post('/imagens/conteudo/visibilidade/{id}', [EngenhariaManagerImagensController::class, 'visibilidade'])->name('Engenharia.Manager.Imagens.visibilidade');
            Route::post('/imagens/conteudo/excluir/{id}', [EngenhariaManagerImagensController::class, 'excluir'])->name('Engenharia.Manager.Imagens.excluir');

            Route::post('/enviar-imagem', [EngenhariaManagerFinderController::class, 'enviar'])->name('Engenharia.Manager.Finder.enviar');


            Route::get('/home', [EngenhariaManagerHomeController::class, 'index'])->name('Engenharia.Manager.Home.index');

            Route::post('/slides/ordenar', [EngenhariaManagerSlidesController::class, 'ordenar'])->name('Engenharia.Manager.Slides.ordenar');
            Route::post('/slides/visibilidade/{id}', [EngenhariaManagerSlidesController::class, 'visibilidade'])->name('Engenharia.Manager.Slides.visibilidade');
            Route::post('/slides/excluir/{id}', [EngenhariaManagerSlidesController::class, 'excluir'])->name('Engenharia.Manager.Slides.excluir');

            Route::get('/slides/adicionar/{tipo}', [EngenhariaManagerSlidesController::class, 'adicionar'])->name('Engenharia.Manager.Slides.adicionar');
            Route::post('/slides/adicionar/{tipo}', [EngenhariaManagerSlidesController::class, 'novo'])->name('Engenharia.Manager.Slides.novo');
            Route::get('/slides/editar/{id}', [EngenhariaManagerSlidesController::class, 'editar'])->name('Engenharia.Manager.Slides.editar');
            Route::post('/slides/editar/{id}', [EngenhariaManagerSlidesController::class, 'atualizar'])->name('Engenharia.Manager.Slides.atualizar');
            Route::get('/slides/baixar-video/{id}/{video}', [EngenhariaManagerSlidesController::class, 'baixarVideo'])->name('Engenharia.Manager.Slides.baixarVideo');


            Route::post('/setores/ordenar', [EngenhariaManagerSetoresController::class, 'ordenar'])->name('Engenharia.Manager.Setores.ordenar');
            Route::post('/setores/visibilidade/{id}', [EngenhariaManagerSetoresController::class, 'visibilidade'])->name('Engenharia.Manager.Setores.visibilidade');
            Route::post('/setores/excluir/{id}', [EngenhariaManagerSetoresController::class, 'excluir'])->name('Engenharia.Manager.Setores.excluir');

            Route::get('/setores/adicionar', [EngenhariaManagerSetoresController::class, 'adicionar'])->name('Engenharia.Manager.Setores.adicionar');
            Route::post('/setores/adicionar', [EngenhariaManagerSetoresController::class, 'novo'])->name('Engenharia.Manager.Setores.novo');
            Route::get('/setores/editar/{id}', [EngenhariaManagerSetoresController::class, 'editar'])->name('Engenharia.Manager.Setores.editar');
            Route::post('/setores/editar/{id}', [EngenhariaManagerSetoresController::class, 'atualizar'])->name('Engenharia.Manager.Setores.atualizar');


            Route::get('/cases', [EngenhariaManagerCasesController::class, 'index'])->name('Engenharia.Manager.Cases.index');

            Route::post('/cases/ordenar', [EngenhariaManagerCasesController::class, 'ordenar'])->name('Engenharia.Manager.Cases.ordenar');
            Route::post('/cases/visibilidade/{id}', [EngenhariaManagerCasesController::class, 'visibilidade'])->name('Engenharia.Manager.Cases.visibilidade');
            Route::post('/cases/excluir/{id}', [EngenhariaManagerCasesController::class, 'excluir'])->name('Engenharia.Manager.Cases.excluir');

            Route::get('/cases/adicionar', [EngenhariaManagerCasesController::class, 'adicionar'])->name('Engenharia.Manager.Cases.adicionar');
            Route::post('/cases/adicionar', [EngenhariaManagerCasesController::class, 'novo'])->name('Engenharia.Manager.Cases.novo');
            Route::get('/cases/editar/{id}', [EngenhariaManagerCasesController::class, 'editar'])->name('Engenharia.Manager.Cases.editar');
            Route::post('/cases/editar/{id}', [EngenhariaManagerCasesController::class, 'atualizar'])->name('Engenharia.Manager.Cases.atualizar');

            Route::get('/cases/blocos/{id}', [EngenhariaManagerBlocosCasesController::class, 'index'])->name('Engenharia.Manager.Cases.Blocos.index');
            Route::post('/cases/blocos/ordenar', [EngenhariaManagerBlocosCasesController::class, 'ordenar'])->name('Engenharia.Manager.Cases.Blocos.ordenar');
            Route::post('/cases/blocos/visibilidade/{id}', [EngenhariaManagerBlocosCasesController::class, 'visibilidade'])->name('Engenharia.Manager.Cases.Blocos.visibilidade');
            Route::post('/cases/blocos/excluir/{id}', [EngenhariaManagerBlocosCasesController::class, 'excluir'])->name('Engenharia.Manager.Cases.Blocos.excluir');

            Route::get('/cases/blocos/adicionar/{id}', [EngenhariaManagerBlocosCasesController::class, 'adicionar'])->name('Engenharia.Manager.Cases.Blocos.adicionar');
            Route::post('/cases/blocos/adicionar/{id}', [EngenhariaManagerBlocosCasesController::class, 'novo'])->name('Engenharia.Manager.Cases.Blocos.novo');
            Route::get('/cases/blocos/editar/{id}', [EngenhariaManagerBlocosCasesController::class, 'editar'])->name('Engenharia.Manager.Cases.Blocos.editar');
            Route::post('/cases/blocos/editar/{id}', [EngenhariaManagerBlocosCasesController::class, 'atualizar'])->name('Engenharia.Manager.Cases.Blocos.atualizar');

            Route::get('/cases/imagens/{id}', [EngenhariaManagerImagensCasesController::class, 'index'])->name('Engenharia.Manager.Cases.Imagens.index');
            Route::post('/cases/imagens/adicionar/{id}', [EngenhariaManagerImagensCasesController::class, 'novo'])->name('Engenharia.Manager.Cases.Imagens.novo');

            Route::post('/cases/imagens/cortar/{id}', [EngenhariaManagerImagensCasesController::class, 'cortar'])->name('Engenharia.Manager.Cases.Imagens.cortar');
            Route::post('/cases/imagens/ordenar/{id}', [EngenhariaManagerImagensCasesController::class, 'ordenar'])->name('Engenharia.Manager.Cases.Imagens.ordenar');
            Route::post('/cases/imagens/visibilidade/{id}', [EngenhariaManagerImagensCasesController::class, 'visibilidade'])->name('Engenharia.Manager.Cases.Imagens.visibilidade');
            Route::post('/cases/imagens/excluir/{id}', [EngenhariaManagerImagensCasesController::class, 'excluir'])->name('Engenharia.Manager.Cases.Imagens.excluir');


            Route::post('/depoimentos/ordenar', [EngenhariaManagerDepoimentosController::class, 'ordenar'])->name('Engenharia.Manager.Depoimentos.ordenar');
            Route::post('/depoimentos/visibilidade/{id}', [EngenhariaManagerDepoimentosController::class, 'visibilidade'])->name('Engenharia.Manager.Depoimentos.visibilidade');
            Route::post('/depoimentos/excluir/{id}', [EngenhariaManagerDepoimentosController::class, 'excluir'])->name('Engenharia.Manager.Depoimentos.excluir');

            Route::get('/depoimentos/adicionar', [EngenhariaManagerDepoimentosController::class, 'adicionar'])->name('Engenharia.Manager.Depoimentos.adicionar');
            Route::post('/depoimentos/adicionar', [EngenhariaManagerDepoimentosController::class, 'novo'])->name('Engenharia.Manager.Depoimentos.novo');
            Route::get('/depoimentos/editar/{id}', [EngenhariaManagerDepoimentosController::class, 'editar'])->name('Engenharia.Manager.Depoimentos.editar');
            Route::post('/depoimentos/editar/{id}', [EngenhariaManagerDepoimentosController::class, 'atualizar'])->name('Engenharia.Manager.Depoimentos.atualizar');


            Route::post('/clientes/ordenar', [EngenhariaManagerClientesController::class, 'ordenar'])->name('Engenharia.Manager.Clientes.ordenar');
            Route::post('/clientes/visibilidade/{id}', [EngenhariaManagerClientesController::class, 'visibilidade'])->name('Engenharia.Manager.Clientes.visibilidade');
            Route::post('/clientes/excluir/{id}', [EngenhariaManagerClientesController::class, 'excluir'])->name('Engenharia.Manager.Clientes.excluir');

            Route::get('/clientes/adicionar', [EngenhariaManagerClientesController::class, 'adicionar'])->name('Engenharia.Manager.Clientes.adicionar');
            Route::post('/clientes/adicionar', [EngenhariaManagerClientesController::class, 'novo'])->name('Engenharia.Manager.Clientes.novo');
            Route::get('/clientes/editar/{id}', [EngenhariaManagerClientesController::class, 'editar'])->name('Engenharia.Manager.Clientes.editar');
            Route::post('/clientes/editar/{id}', [EngenhariaManagerClientesController::class, 'atualizar'])->name('Engenharia.Manager.Clientes.atualizar');


            Route::get('/institucional', [EngenhariaManagerInstitucionalController::class, 'index'])->name('Engenharia.Manager.Institucional.index');


            Route::post('/membros/ordenar', [EngenhariaManagerMembrosController::class, 'ordenar'])->name('Engenharia.Manager.Membros.ordenar');
            Route::post('/membros/visibilidade/{id}', [EngenhariaManagerMembrosController::class, 'visibilidade'])->name('Engenharia.Manager.Membros.visibilidade');
            Route::post('/membros/excluir/{id}', [EngenhariaManagerMembrosController::class, 'excluir'])->name('Engenharia.Manager.Membros.excluir');

            Route::get('/membros/adicionar', [EngenhariaManagerMembrosController::class, 'adicionar'])->name('Engenharia.Manager.Membros.adicionar');
            Route::post('/membros/adicionar', [EngenhariaManagerMembrosController::class, 'novo'])->name('Engenharia.Manager.Membros.novo');
            Route::get('/membros/editar/{id}', [EngenhariaManagerMembrosController::class, 'editar'])->name('Engenharia.Manager.Membros.editar');
            Route::post('/membros/editar/{id}', [EngenhariaManagerMembrosController::class, 'atualizar'])->name('Engenharia.Manager.Membros.atualizar');


            Route::post('/certificacoes/ordenar', [EngenhariaManagerCertificacoesController::class, 'ordenar'])->name('Engenharia.Manager.Certificacoes.ordenar');
            Route::post('/certificacoes/visibilidade/{id}', [EngenhariaManagerCertificacoesController::class, 'visibilidade'])->name('Engenharia.Manager.Certificacoes.visibilidade');
            Route::post('/certificacoes/excluir/{id}', [EngenhariaManagerCertificacoesController::class, 'excluir'])->name('Engenharia.Manager.Certificacoes.excluir');

            Route::get('/certificacoes/adicionar', [EngenhariaManagerCertificacoesController::class, 'adicionar'])->name('Engenharia.Manager.Certificacoes.adicionar');
            Route::post('/certificacoes/adicionar', [EngenhariaManagerCertificacoesController::class, 'novo'])->name('Engenharia.Manager.Certificacoes.novo');
            Route::get('/certificacoes/editar/{id}', [EngenhariaManagerCertificacoesController::class, 'editar'])->name('Engenharia.Manager.Certificacoes.editar');
            Route::post('/certificacoes/editar/{id}', [EngenhariaManagerCertificacoesController::class, 'atualizar'])->name('Engenharia.Manager.Certificacoes.atualizar');


            Route::get('/produtos', [EngenhariaManagerProdutosController::class, 'index'])->name('Engenharia.Manager.Produtos.index');

            Route::post('/produtos/ordenar', [EngenhariaManagerProdutosController::class, 'ordenar'])->name('Engenharia.Manager.Produtos.ordenar');
            Route::post('/produtos/visibilidade/{id}', [EngenhariaManagerProdutosController::class, 'visibilidade'])->name('Engenharia.Manager.Produtos.visibilidade');
            Route::post('/produtos/excluir/{id}', [EngenhariaManagerProdutosController::class, 'excluir'])->name('Engenharia.Manager.Produtos.excluir');

            Route::get('/produtos/adicionar', [EngenhariaManagerProdutosController::class, 'adicionar'])->name('Engenharia.Manager.Produtos.adicionar');
            Route::post('/produtos/adicionar', [EngenhariaManagerProdutosController::class, 'novo'])->name('Engenharia.Manager.Produtos.novo');
            Route::get('/produtos/editar/{id}', [EngenhariaManagerProdutosController::class, 'editar'])->name('Engenharia.Manager.Produtos.editar');
            Route::post('/produtos/editar/{id}', [EngenhariaManagerProdutosController::class, 'atualizar'])->name('Engenharia.Manager.Produtos.atualizar');


            Route::get('/servicos', [EngenhariaManagerServicosController::class, 'index'])->name('Engenharia.Manager.Servicos.index');

            Route::post('/etapas/ordenar', [EngenhariaManagerEtapasController::class, 'ordenar'])->name('Engenharia.Manager.Etapas.ordenar');
            Route::post('/etapas/visibilidade/{id}', [EngenhariaManagerEtapasController::class, 'visibilidade'])->name('Engenharia.Manager.Etapas.visibilidade');
            Route::post('/etapas/excluir/{id}', [EngenhariaManagerEtapasController::class, 'excluir'])->name('Engenharia.Manager.Etapas.excluir');

            Route::get('/etapas/adicionar', [EngenhariaManagerEtapasController::class, 'adicionar'])->name('Engenharia.Manager.Etapas.adicionar');
            Route::post('/etapas/adicionar', [EngenhariaManagerEtapasController::class, 'novo'])->name('Engenharia.Manager.Etapas.novo');
            Route::get('/etapas/editar/{id}', [EngenhariaManagerEtapasController::class, 'editar'])->name('Engenharia.Manager.Etapas.editar');
            Route::post('/etapas/editar/{id}', [EngenhariaManagerEtapasController::class, 'atualizar'])->name('Engenharia.Manager.Etapas.atualizar');


            Route::get('/news', [EngenhariaManagerNewsController::class, 'index'])->name('Engenharia.Manager.News.index');

            Route::post('/posts/ordenar', [EngenhariaManagerPostsController::class, 'ordenar'])->name('Engenharia.Manager.Posts.ordenar');
            Route::post('/posts/visibilidade/{id}', [EngenhariaManagerPostsController::class, 'visibilidade'])->name('Engenharia.Manager.Posts.visibilidade');
            Route::post('/posts/excluir/{id}', [EngenhariaManagerPostsController::class, 'excluir'])->name('Engenharia.Manager.Posts.excluir');

            Route::get('/posts/adicionar', [EngenhariaManagerPostsController::class, 'adicionar'])->name('Engenharia.Manager.Posts.adicionar');
            Route::post('/posts/adicionar', [EngenhariaManagerPostsController::class, 'novo'])->name('Engenharia.Manager.Posts.novo');
            Route::get('/posts/editar/{id}', [EngenhariaManagerPostsController::class, 'editar'])->name('Engenharia.Manager.Posts.editar');
            Route::post('/posts/editar/{id}', [EngenhariaManagerPostsController::class, 'atualizar'])->name('Engenharia.Manager.Posts.atualizar');


            Route::post('/posts/categorias/ordenar', [EngenhariaManagerPostsCategoriasController::class, 'ordenar'])->name('Engenharia.Manager.Posts.Categorias.ordenar');
            Route::post('/posts/categorias/visibilidade/{id}', [EngenhariaManagerPostsCategoriasController::class, 'visibilidade'])->name('Engenharia.Manager.Posts.Categorias.visibilidade');
            Route::post('/posts/categorias/excluir/{id}', [EngenhariaManagerPostsCategoriasController::class, 'excluir'])->name('Engenharia.Manager.Posts.Categorias.excluir');

            Route::get('/posts/categorias/adicionar', [EngenhariaManagerPostsCategoriasController::class, 'adicionar'])->name('Engenharia.Manager.Posts.Categorias.adicionar');
            Route::post('/posts/categorias/adicionar', [EngenhariaManagerPostsCategoriasController::class, 'novo'])->name('Engenharia.Manager.Posts.Categorias.novo');
            Route::get('/posts/categorias/editar/{id}', [EngenhariaManagerPostsCategoriasController::class, 'editar'])->name('Engenharia.Manager.Posts.Categorias.editar');
            Route::post('/posts/categorias/editar/{id}', [EngenhariaManagerPostsCategoriasController::class, 'atualizar'])->name('Engenharia.Manager.Posts.Categorias.atualizar');


            Route::get('/contato', [EngenhariaManagerContatoController::class, 'index'])->name('Engenharia.Manager.Contato.index');
            Route::get('/contato/visualizar/{id}', [EngenhariaManagerContatoController::class, 'visualizar'])->name('Engenharia.Manager.Contato.visualizar');
            Route::post('/contato/excluir/{id}', [EngenhariaManagerContatoController::class, 'excluir'])->name('Engenharia.Manager.Contato.excluir');


            // Route::get('/orcamentos', [EngenhariaManagerOrcamentosController::class, 'index'])->name('Engenharia.Manager.Orcamentos.index');
            // Route::get('/orcamentos/visualizar/{id}', [EngenhariaManagerOrcamentosController::class, 'visualizar'])->name('Engenharia.Manager.Orcamentos.visualizar');
            // Route::post('/orcamentos/excluir/{id}', [EngenhariaManagerOrcamentosController::class, 'excluir'])->name('Engenharia.Manager.Orcamentos.excluir');


            Route::get('/politicas/privacidade', [EngenhariaManagerPoliticasController::class, 'privacidade'])->name('Engenharia.Manager.Politicas.privacidade');
        });
    });
});

Route::prefix('/enologia')->group(function() {
    Route::prefix('/manager')->group(function() {
        Route::get('/', [EnologiaUsuariosController::class, 'login'])->name('Enologia.Manager.Usuarios.login');
        Route::post('/', ['as' => 'enologia.login', 'uses' => 'App\Http\Controllers\Enologia\Manager\UsuariosController@authenticate']);

        Route::post('/usuarios/logout', [EnologiaUsuariosController::class, 'logout'])->name('Enologia.Manager.Usuarios.logout');

        Route::group(['middleware' => ['auth:enologia']], function() {
            Route::post('/paginas/editar/{id}', [EnologiaManagerPaginasController::class, 'editarAction'])->name('Enologia.Manager.Paginas.editar');

            Route::post('/conteudos/editar/{id}', [EnologiaManagerConteudosController::class, 'editarAction'])->name('Enologia.Manager.Conteudos.editar');
            Route::post('/conteudos/baixar-arquivo/{id}', [EnologiaManagerConteudosController::class, 'baixarArquivo'])->name('Enologia.Manager.Conteudos.baixarArquivo');

            Route::get('/imagens/{id}', [EnologiaManagerImagensController::class, 'conteudo'])->name('Enologia.Manager.Imagens.conteudo');
            Route::post('/imagens/conteudo/adicionar/{id}', [EnologiaManagerImagensController::class, 'novo'])->name('Enologia.Manager.Imagens.novo');
            
            Route::post('/imagens/conteudo/ordenar/{id}', [EnologiaManagerImagensController::class, 'ordenar'])->name('Enologia.Manager.Imagens.ordenar');
            Route::post('/imagens/conteudo/visibilidade/{id}', [EnologiaManagerImagensController::class, 'visibilidade'])->name('Enologia.Manager.Imagens.visibilidade');
            Route::post('/imagens/conteudo/excluir/{id}', [EnologiaManagerImagensController::class, 'excluir'])->name('Enologia.Manager.Imagens.excluir');

            Route::post('/enviar-imagem', [EnologiaManagerFinderController::class, 'enviar'])->name('Enologia.Manager.Finder.enviar');


            Route::get('/home', [EnologiaManagerHomeController::class, 'index'])->name('Enologia.Manager.Home.index');

            Route::post('/slides/ordenar', [EnologiaManagerSlidesController::class, 'ordenar'])->name('Enologia.Manager.Slides.ordenar');
            Route::post('/slides/visibilidade/{id}', [EnologiaManagerSlidesController::class, 'visibilidade'])->name('Enologia.Manager.Slides.visibilidade');
            Route::post('/slides/excluir/{id}', [EnologiaManagerSlidesController::class, 'excluir'])->name('Enologia.Manager.Slides.excluir');

            Route::get('/slides/adicionar/{tipo}', [EnologiaManagerSlidesController::class, 'adicionar'])->name('Enologia.Manager.Slides.adicionar');
            Route::post('/slides/adicionar/{tipo}', [EnologiaManagerSlidesController::class, 'novo'])->name('Enologia.Manager.Slides.novo');
            Route::get('/slides/editar/{id}', [EnologiaManagerSlidesController::class, 'editar'])->name('Enologia.Manager.Slides.editar');
            Route::post('/slides/editar/{id}', [EnologiaManagerSlidesController::class, 'atualizar'])->name('Enologia.Manager.Slides.atualizar');
            Route::get('/slides/baixar-video/{id}/{video}', [EnologiaManagerSlidesController::class, 'baixarVideo'])->name('Enologia.Manager.Slides.baixarVideo');


            Route::post('/solucoes/ordenar', [EnologiaManagerSolucoesController::class, 'ordenar'])->name('Enologia.Manager.Solucoes.ordenar');
            Route::post('/solucoes/visibilidade/{id}', [EnologiaManagerSolucoesController::class, 'visibilidade'])->name('Enologia.Manager.Solucoes.visibilidade');
            Route::post('/solucoes/excluir/{id}', [EnologiaManagerSolucoesController::class, 'excluir'])->name('Enologia.Manager.Solucoes.excluir');

            Route::get('/solucoes/adicionar', [EnologiaManagerSolucoesController::class, 'adicionar'])->name('Enologia.Manager.Solucoes.adicionar');
            Route::post('/solucoes/adicionar', [EnologiaManagerSolucoesController::class, 'novo'])->name('Enologia.Manager.Solucoes.novo');
            Route::get('/solucoes/editar/{id}', [EnologiaManagerSolucoesController::class, 'editar'])->name('Enologia.Manager.Solucoes.editar');
            Route::post('/solucoes/editar/{id}', [EnologiaManagerSolucoesController::class, 'atualizar'])->name('Enologia.Manager.Solucoes.atualizar');


            Route::post('/parceiros/ordenar', [EnologiaManagerParceirosController::class, 'ordenar'])->name('Enologia.Manager.Parceiros.ordenar');
            Route::post('/parceiros/visibilidade/{id}', [EnologiaManagerParceirosController::class, 'visibilidade'])->name('Enologia.Manager.Parceiros.visibilidade');
            Route::post('/parceiros/excluir/{id}', [EnologiaManagerParceirosController::class, 'excluir'])->name('Enologia.Manager.Parceiros.excluir');

            Route::get('/parceiros/adicionar', [EnologiaManagerParceirosController::class, 'adicionar'])->name('Enologia.Manager.Parceiros.adicionar');
            Route::post('/parceiros/adicionar', [EnologiaManagerParceirosController::class, 'novo'])->name('Enologia.Manager.Parceiros.novo');
            Route::get('/parceiros/editar/{id}', [EnologiaManagerParceirosController::class, 'editar'])->name('Enologia.Manager.Parceiros.editar');
            Route::post('/parceiros/editar/{id}', [EnologiaManagerParceirosController::class, 'atualizar'])->name('Enologia.Manager.Parceiros.atualizar');


            Route::get('/institucional', [EnologiaManagerInstitucionalController::class, 'index'])->name('Enologia.Manager.Institucional.index');

            Route::post('/acontecimentos/ordenar', [EnologiaManagerAcontecimentosController::class, 'ordenar'])->name('Enologia.Manager.Acontecimentos.ordenar');
            Route::post('/acontecimentos/visibilidade/{id}', [EnologiaManagerAcontecimentosController::class, 'visibilidade'])->name('Enologia.Manager.Acontecimentos.visibilidade');
            Route::post('/acontecimentos/excluir/{id}', [EnologiaManagerAcontecimentosController::class, 'excluir'])->name('Enologia.Manager.Acontecimentos.excluir');

            Route::get('/acontecimentos/adicionar', [EnologiaManagerAcontecimentosController::class, 'adicionar'])->name('Enologia.Manager.Acontecimentos.adicionar');
            Route::post('/acontecimentos/adicionar', [EnologiaManagerAcontecimentosController::class, 'novo'])->name('Enologia.Manager.Acontecimentos.novo');
            Route::get('/acontecimentos/editar/{id}', [EnologiaManagerAcontecimentosController::class, 'editar'])->name('Enologia.Manager.Acontecimentos.editar');
            Route::post('/acontecimentos/editar/{id}', [EnologiaManagerAcontecimentosController::class, 'atualizar'])->name('Enologia.Manager.Acontecimentos.atualizar');

            
            Route::get('/projetos', [EnologiaManagerProjetosController::class, 'index'])->name('Enologia.Manager.Projetos.index');

            Route::post('/etapas/ordenar', [EnologiaManagerEtapasController::class, 'ordenar'])->name('Enologia.Manager.Etapas.ordenar');
            Route::post('/etapas/visibilidade/{id}', [EnologiaManagerEtapasController::class, 'visibilidade'])->name('Enologia.Manager.Etapas.visibilidade');
            Route::post('/etapas/excluir/{id}', [EnologiaManagerEtapasController::class, 'excluir'])->name('Enologia.Manager.Etapas.excluir');

            Route::get('/etapas/adicionar', [EnologiaManagerEtapasController::class, 'adicionar'])->name('Enologia.Manager.Etapas.adicionar');
            Route::post('/etapas/adicionar', [EnologiaManagerEtapasController::class, 'novo'])->name('Enologia.Manager.Etapas.novo');
            Route::get('/etapas/editar/{id}', [EnologiaManagerEtapasController::class, 'editar'])->name('Enologia.Manager.Etapas.editar');
            Route::post('/etapas/editar/{id}', [EnologiaManagerEtapasController::class, 'atualizar'])->name('Enologia.Manager.Etapas.atualizar');

            
            Route::get('/insumos', [EnologiaManagerInsumosController::class, 'index'])->name('Enologia.Manager.Insumos.index');

            Route::post('/insumos/categorias/ordenar', [EnologiaManagerInsumosCategoriasController::class, 'ordenar'])->name('Enologia.Manager.Insumos.Categorias.ordenar');
            Route::post('/insumos/categorias/visibilidade/{id}', [EnologiaManagerInsumosCategoriasController::class, 'visibilidade'])->name('Enologia.Manager.Insumos.Categorias.visibilidade');
            Route::post('/insumos/categorias/excluir/{id}', [EnologiaManagerInsumosCategoriasController::class, 'excluir'])->name('Enologia.Manager.Insumos.Categorias.excluir');

            Route::get('/insumos/categorias/adicionar', [EnologiaManagerInsumosCategoriasController::class, 'adicionar'])->name('Enologia.Manager.Insumos.Categorias.adicionar');
            Route::post('/insumos/categorias/adicionar', [EnologiaManagerInsumosCategoriasController::class, 'novo'])->name('Enologia.Manager.Insumos.Categorias.novo');
            Route::get('/insumos/categorias/editar/{id}', [EnologiaManagerInsumosCategoriasController::class, 'editar'])->name('Enologia.Manager.Insumos.Categorias.editar');
            Route::post('/insumos/categorias/editar/{id}', [EnologiaManagerInsumosCategoriasController::class, 'atualizar'])->name('Enologia.Manager.Insumos.Categorias.atualizar');


            Route::post('/insumos/subcategorias/ordenar', [EnologiaManagerInsumosSubcategoriasController::class, 'ordenar'])->name('Enologia.Manager.Insumos.Subcategorias.ordenar');
            Route::post('/insumos/subcategorias/visibilidade/{id}', [EnologiaManagerInsumosSubcategoriasController::class, 'visibilidade'])->name('Enologia.Manager.Insumos.Subcategorias.visibilidade');
            Route::post('/insumos/subcategorias/excluir/{id}', [EnologiaManagerInsumosSubcategoriasController::class, 'excluir'])->name('Enologia.Manager.Insumos.Subcategorias.excluir');

            Route::get('/insumos/subcategorias/adicionar', [EnologiaManagerInsumosSubcategoriasController::class, 'adicionar'])->name('Enologia.Manager.Insumos.Subcategorias.adicionar');
            Route::post('/insumos/subcategorias/adicionar', [EnologiaManagerInsumosSubcategoriasController::class, 'novo'])->name('Enologia.Manager.Insumos.Subcategorias.novo');
            Route::get('/insumos/subcategorias/editar/{id}', [EnologiaManagerInsumosSubcategoriasController::class, 'editar'])->name('Enologia.Manager.Insumos.Subcategorias.editar');
            Route::post('/insumos/subcategorias/editar/{id}', [EnologiaManagerInsumosSubcategoriasController::class, 'atualizar'])->name('Enologia.Manager.Insumos.Subcategorias.atualizar');


            Route::post('/marcas/ordenar', [EnologiaManagerMarcasController::class, 'ordenar'])->name('Enologia.Manager.Marcas.ordenar');
            Route::post('/marcas/visibilidade/{id}', [EnologiaManagerMarcasController::class, 'visibilidade'])->name('Enologia.Manager.Marcas.visibilidade');
            Route::post('/marcas/excluir/{id}', [EnologiaManagerMarcasController::class, 'excluir'])->name('Enologia.Manager.Marcas.excluir');

            Route::get('/marcas/adicionar', [EnologiaManagerMarcasController::class, 'adicionar'])->name('Enologia.Manager.Marcas.adicionar');
            Route::post('/marcas/adicionar', [EnologiaManagerMarcasController::class, 'novo'])->name('Enologia.Manager.Marcas.novo');
            Route::get('/marcas/editar/{id}', [EnologiaManagerMarcasController::class, 'editar'])->name('Enologia.Manager.Marcas.editar');
            Route::post('/marcas/editar/{id}', [EnologiaManagerMarcasController::class, 'atualizar'])->name('Enologia.Manager.Marcas.atualizar');


            Route::get('/insumos/produtos/{id}', [EnologiaManagerInsumosProdutosController::class, 'index'])->name('Enologia.Manager.Insumos.Produtos.index');

            Route::post('/insumos/produtos/ordenar', [EnologiaManagerInsumosProdutosController::class, 'ordenar'])->name('Enologia.Manager.Insumos.Produtos.ordenar');
            Route::post('/insumos/produtos/visibilidade/{id}', [EnologiaManagerInsumosProdutosController::class, 'visibilidade'])->name('Enologia.Manager.Insumos.Produtos.visibilidade');
            Route::post('/insumos/produtos/excluir/{id}', [EnologiaManagerInsumosProdutosController::class, 'excluir'])->name('Enologia.Manager.Insumos.Produtos.excluir');

            Route::get('/insumos/produtos/adicionar/{id}', [EnologiaManagerInsumosProdutosController::class, 'adicionar'])->name('Enologia.Manager.Insumos.Produtos.adicionar');
            Route::post('/insumos/produtos/adicionar/{id}', [EnologiaManagerInsumosProdutosController::class, 'novo'])->name('Enologia.Manager.Insumos.Produtos.novo');
            Route::get('/insumos/produtos/editar/{id}', [EnologiaManagerInsumosProdutosController::class, 'editar'])->name('Enologia.Manager.Insumos.Produtos.editar');
            Route::post('/insumos/produtos/editar/{id}', [EnologiaManagerInsumosProdutosController::class, 'atualizar'])->name('Enologia.Manager.Insumos.Produtos.atualizar');
            Route::get('/insumos/produtos/baixar-arquivo/{id}/{video}', [EnologiaManagerInsumosProdutosController::class, 'baixarArquivo'])->name('Enologia.Manager.Insumos.Produtos.baixarArquivo');


            Route::get('/automacao', [EnologiaManagerAutomacaoController::class, 'index'])->name('Enologia.Manager.Automacao.index');

            Route::post('/processos/ordenar', [EngenhariaManagerProcessosController::class, 'ordenar'])->name('Enologia.Manager.Processos.ordenar');
            Route::post('/processos/visibilidade/{id}', [EngenhariaManagerProcessosController::class, 'visibilidade'])->name('Enologia.Manager.Processos.visibilidade');
            Route::post('/processos/excluir/{id}', [EngenhariaManagerProcessosController::class, 'excluir'])->name('Enologia.Manager.Processos.excluir');

            Route::get('/processos/adicionar', [EngenhariaManagerProcessosController::class, 'adicionar'])->name('Enologia.Manager.Processos.adicionar');
            Route::post('/processos/adicionar', [EngenhariaManagerProcessosController::class, 'novo'])->name('Enologia.Manager.Processos.novo');
            Route::get('/processos/editar/{id}', [EngenhariaManagerProcessosController::class, 'editar'])->name('Enologia.Manager.Processos.editar');
            Route::post('/processos/editar/{id}', [EngenhariaManagerProcessosController::class, 'atualizar'])->name('Enologia.Manager.Processos.atualizar');

            Route::get('/processos/imagens/{id}', [EnologiamaNagerimAgensprOcessoscoNtroller::class, 'index'])->name('Enologia.Manager.Processos.Imagens.index');
            Route::post('/processos/imagens/adicionar/{id}', [EnologiamaNagerimAgensprOcessoscoNtroller::class, 'novo'])->name('Enologia.Manager.Processos.Imagens.novo');

            Route::post('/processos/imagens/cortar/{id}', [EnologiamaNagerimAgensprOcessoscoNtroller::class, 'cortar'])->name('Enologia.Manager.Processos.Imagens.cortar');
            Route::post('/processos/imagens/ordenar/{id}', [EnologiamaNagerimAgensprOcessoscoNtroller::class, 'ordenar'])->name('Enologia.Manager.Processos.Imagens.ordenar');
            Route::post('/processos/imagens/visibilidade/{id}', [EnologiamaNagerimAgensprOcessoscoNtroller::class, 'visibilidade'])->name('Enologia.Manager.Processos.Imagens.visibilidade');
            Route::post('/processos/imagens/excluir/{id}', [EnologiamaNagerimAgensprOcessoscoNtroller::class, 'excluir'])->name('Enologia.Manager.Processos.Imagens.excluir');


            Route::get('/equipamentos', [EnologiaManagerEquipamentosController::class, 'index'])->name('Enologia.Manager.Equipamentos.index');

            Route::post('/equipamentos/categorias/ordenar', [EnologiaManagerEquipamentosCategoriasController::class, 'ordenar'])->name('Enologia.Manager.Equipamentos.Categorias.ordenar');
            Route::post('/equipamentos/categorias/visibilidade/{id}', [EnologiaManagerEquipamentosCategoriasController::class, 'visibilidade'])->name('Enologia.Manager.Equipamentos.Categorias.visibilidade');
            Route::post('/equipamentos/categorias/excluir/{id}', [EnologiaManagerEquipamentosCategoriasController::class, 'excluir'])->name('Enologia.Manager.Equipamentos.Categorias.excluir');

            Route::get('/equipamentos/categorias/adicionar', [EnologiaManagerEquipamentosCategoriasController::class, 'adicionar'])->name('Enologia.Manager.Equipamentos.Categorias.adicionar');
            Route::post('/equipamentos/categorias/adicionar', [EnologiaManagerEquipamentosCategoriasController::class, 'novo'])->name('Enologia.Manager.Equipamentos.Categorias.novo');
            Route::get('/equipamentos/categorias/editar/{id}', [EnologiaManagerEquipamentosCategoriasController::class, 'editar'])->name('Enologia.Manager.Equipamentos.Categorias.editar');
            Route::post('/equipamentos/categorias/editar/{id}', [EnologiaManagerEquipamentosCategoriasController::class, 'atualizar'])->name('Enologia.Manager.Equipamentos.Categorias.atualizar');


            Route::post('/equipamentos/subcategorias/ordenar', [EnologiaManagerEquipamentosSubcategoriasController::class, 'ordenar'])->name('Enologia.Manager.Equipamentos.Subcategorias.ordenar');
            Route::post('/equipamentos/subcategorias/visibilidade/{id}', [EnologiaManagerEquipamentosSubcategoriasController::class, 'visibilidade'])->name('Enologia.Manager.Equipamentos.Subcategorias.visibilidade');
            Route::post('/equipamentos/subcategorias/excluir/{id}', [EnologiaManagerEquipamentosSubcategoriasController::class, 'excluir'])->name('Enologia.Manager.Equipamentos.Subcategorias.excluir');

            Route::get('/equipamentos/subcategorias/adicionar', [EnologiaManagerEquipamentosSubcategoriasController::class, 'adicionar'])->name('Enologia.Manager.Equipamentos.Subcategorias.adicionar');
            Route::post('/equipamentos/subcategorias/adicionar', [EnologiaManagerEquipamentosSubcategoriasController::class, 'novo'])->name('Enologia.Manager.Equipamentos.Subcategorias.novo');
            Route::get('/equipamentos/subcategorias/editar/{id}', [EnologiaManagerEquipamentosSubcategoriasController::class, 'editar'])->name('Enologia.Manager.Equipamentos.Subcategorias.editar');
            Route::post('/equipamentos/subcategorias/editar/{id}', [EnologiaManagerEquipamentosSubcategoriasController::class, 'atualizar'])->name('Enologia.Manager.Equipamentos.Subcategorias.atualizar');


            Route::get('/equipamentos/produtos/{id}', [EnologiaManagerEquipamentosProdutosController::class, 'index'])->name('Enologia.Manager.Equipamentos.Produtos.index');

            Route::post('/equipamentos/produtos/ordenar', [EnologiaManagerEquipamentosProdutosController::class, 'ordenar'])->name('Enologia.Manager.Equipamentos.Produtos.ordenar');
            Route::post('/equipamentos/produtos/visibilidade/{id}', [EnologiaManagerEquipamentosProdutosController::class, 'visibilidade'])->name('Enologia.Manager.Equipamentos.Produtos.visibilidade');
            Route::post('/equipamentos/produtos/excluir/{id}', [EnologiaManagerEquipamentosProdutosController::class, 'excluir'])->name('Enologia.Manager.Equipamentos.Produtos.excluir');

            Route::get('/equipamentos/produtos/adicionar/{id}', [EnologiaManagerEquipamentosProdutosController::class, 'adicionar'])->name('Enologia.Manager.Equipamentos.Produtos.adicionar');
            Route::post('/equipamentos/produtos/adicionar/{id}', [EnologiaManagerEquipamentosProdutosController::class, 'novo'])->name('Enologia.Manager.Equipamentos.Produtos.novo');
            Route::get('/equipamentos/produtos/editar/{id}', [EnologiaManagerEquipamentosProdutosController::class, 'editar'])->name('Enologia.Manager.Equipamentos.Produtos.editar');
            Route::post('/equipamentos/produtos/editar/{id}', [EnologiaManagerEquipamentosProdutosController::class, 'atualizar'])->name('Enologia.Manager.Equipamentos.Produtos.atualizar');
            Route::get('/equipamentos/produtos/baixar-arquivo/{id}/{video}', [EnologiaManagerEquipamentosProdutosController::class, 'baixarArquivo'])->name('Enologia.Manager.Equipamentos.Produtos.baixarArquivo');


            Route::get('/news', [EnologiaManagerNewsController::class, 'index'])->name('Enologia.Manager.News.index');

            Route::post('/posts/ordenar', [EnologiaManagerPostsController::class, 'ordenar'])->name('Enologia.Manager.Posts.ordenar');
            Route::post('/posts/visibilidade/{id}', [EnologiaManagerPostsController::class, 'visibilidade'])->name('Enologia.Manager.Posts.visibilidade');
            Route::post('/posts/excluir/{id}', [EnologiaManagerPostsController::class, 'excluir'])->name('Enologia.Manager.Posts.excluir');

            Route::get('/posts/adicionar', [EnologiaManagerPostsController::class, 'adicionar'])->name('Enologia.Manager.Posts.adicionar');
            Route::post('/posts/adicionar', [EnologiaManagerPostsController::class, 'novo'])->name('Enologia.Manager.Posts.novo');
            Route::get('/posts/editar/{id}', [EnologiaManagerPostsController::class, 'editar'])->name('Enologia.Manager.Posts.editar');
            Route::post('/posts/editar/{id}', [EnologiaManagerPostsController::class, 'atualizar'])->name('Enologia.Manager.Posts.atualizar');


            Route::post('/posts/categorias/ordenar', [EnologiaManagerPostsCategoriasController::class, 'ordenar'])->name('Enologia.Manager.Posts.Categorias.ordenar');
            Route::post('/posts/categorias/visibilidade/{id}', [EnologiaManagerPostsCategoriasController::class, 'visibilidade'])->name('Enologia.Manager.Posts.Categorias.visibilidade');
            Route::post('/posts/categorias/excluir/{id}', [EnologiaManagerPostsCategoriasController::class, 'excluir'])->name('Enologia.Manager.Posts.Categorias.excluir');

            Route::get('/posts/categorias/adicionar', [EnologiaManagerPostsCategoriasController::class, 'adicionar'])->name('Enologia.Manager.Posts.Categorias.adicionar');
            Route::post('/posts/categorias/adicionar', [EnologiaManagerPostsCategoriasController::class, 'novo'])->name('Enologia.Manager.Posts.Categorias.novo');
            Route::get('/posts/categorias/editar/{id}', [EnologiaManagerPostsCategoriasController::class, 'editar'])->name('Enologia.Manager.Posts.Categorias.editar');
            Route::post('/posts/categorias/editar/{id}', [EnologiaManagerPostsCategoriasController::class, 'atualizar'])->name('Enologia.Manager.Posts.Categorias.atualizar');


            Route::get('/contato', [EnologiaManagerContatoController::class, 'index'])->name('Enologia.Manager.Contato.index');
            Route::get('/contato/visualizar/{id}', [EnologiaManagerContatoController::class, 'visualizar'])->name('Enologia.Manager.Contato.visualizar');
            Route::post('/contato/excluir/{id}', [EEnologiaManagerContatoController::class, 'excluir'])->name('Enologia.Manager.Contato.excluir');


            Route::get('/politicas/privacidade', [EnologiaManagerPoliticasController::class, 'privacidade'])->name('Enologia.Manager.Politicas.privacidade');
        });
    });
});

Route::get('/login', function () {
    $intended = session('url.intended');

    if ($intended) {
        $path = parse_url($intended, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        $prefix = $segments[0] ?? null;

        if ($prefix) {
            if ($prefix == 'engenharia') {
                return to_route('Engenharia.Manager.Usuarios.login')->with('message', ['type' => 'error', 'message' => 'Você precisa fazer login para acessar essa página.']);
            } else if ($prefix == 'enologia') {
                return to_route('Enologia.Manager.Usuarios.login')->with('message', ['type' => 'error', 'message' => 'Você precisa fazer login para acessar essa página.']);
            }
        }
    }
})->name('login');

Auth::routes(['login' => false]);