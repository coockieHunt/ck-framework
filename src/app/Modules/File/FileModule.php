<?php


namespace app\Modules\File;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;


class FileModule extends ModuleFunction
{

    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    /**
     * @var PostsTable
     */

    /**
     * BlogModule constructor.
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @throws Exception
     */
    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container){
        parent::init($router, $renderer, $container, __DIR__);
    }

    /**
     * List route for this module
     * example :
     *     $this->AddRoute(
     *          '/world', {uri}
     *          'index', {function name}
     *          'blog.index' {route name}
     *          'true' [use module prefix !true default}
     *      );
     *
     * @return void
     */
    public function ListRoute(){
        $this->AddRoute(
            '/download/{type:[a-z\-]+}/{id:[0-9]+}',
            [$this, 'downloadFile'],
            'file.download'
        );
    }

    public function downloadFile(Request $request){
        $RequestId = $request->getAttribute('id');
        $RequestType = $request->getAttribute('type');

        $filename = $RequestId . '.' . $RequestType;
        $directory = $this->container->get('download.patch');
        $filepath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filepath)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filepath));
                flush();
                readfile($filepath);
        }else{
            return "file not found : "  . $filename . " please contact admin";
        }
        return null;
    }

}