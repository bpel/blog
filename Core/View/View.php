<?php
namespace Core\View;
use Twig\TwigFunction;
use Twig_Extensions_Extension_Text;

class View
{
    private $viewName;
    private $data;
    private $twig;

    public function __construct($viewName, $data = null)
    {
        $this->viewName = $viewName;
        $this->data = $data;

        $loader = new \Twig\Loader\FilesystemLoader('src/Template');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => 'DEBUG'
        ]);
        $this->twig->addExtension(new Twig_Extensions_Extension_Text());
        $this->twig->addFunction( new TwigFunction(
            'dump',
            [ 'Symfony\Component\VarDumper\VarDumper', 'dump' ]
        ) );
    }

    public function __toString()
    {
        return $this->parseView();

    }

    public function parseView()
    {
        if (is_string($this->data))
        {
            return $this->twig->render($this->viewName.'.twig');
        }
        else {
            return $this->twig->render($this->viewName.'.twig',$this->data);
        }
    }

    public function redirectToPage($pageUrl)
    {
        header("Location: ?page=$pageUrl");
        return 1;
    }

}