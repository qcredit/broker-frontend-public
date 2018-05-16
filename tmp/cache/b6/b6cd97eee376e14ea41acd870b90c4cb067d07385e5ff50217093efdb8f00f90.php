<?php

/* base.twig */
class __TwigTemplate_aa4ce9cb7c613ddd72aa393a61a8aae13d39ca20b5f80b9e33a259867ae2e704 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
            'hero' => array($this, 'block_hero'),
            'container' => array($this, 'block_container'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<html lang=\"en\">
<head>
    <!-- Required meta tags -->
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <meta name=\"google-signin-client_id\" content=\"876809952895-5hg5tv7pj9d0f2bt3chijj2j68ttmi5m.apps.googleusercontent.com\">
    <link rel=\"shortcut icon\" type=\"image/png\" href=\"src/images/favicon.png\"/>
  <!-- Bootstrap CSS -->
    <link rel=\"stylesheet\" href=\"//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\">
    <link rel=\"stylesheet\" href=\"/src/styles/style.css\">
    <title>";
        // line 12
        $this->displayBlock("title", $context, $blocks);
        echo "</title>
</head>
<body>
  <header class=\"page-header\">
      <nav class=\"navbar fixed-top navbar-expand-md navbar-light\">
      <a class=\"navbar-brand\" href=\"/\"><img class=\"navbar-brand\" src=\"/src/images/qcredit_logo.png\" alt=\"logo\"></a>
      <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
        <span class=\"navbar-toggler-icon\"></span>
      </button>
      <div class=\"collapse navbar-collapse\" id=\"navbarNav\">
        <ul class=\"navbar-nav ml-auto\">
          <li class=\"nav-item\">
            <a class=\"nav-link nav-link-outline text-uppercase\" href=\"/application\">Apply for a loan</a>
          </li>
          <li class=\"nav-item\">
            <a class=\"nav-link text-uppercase home-link\" href=\"/\">Home</a>
          </li>
          <li class=\"nav-item\">
            <a class=\"nav-link text-uppercase\" href=\"/about\">About</a>
          </li>
          <li class=\"nav-item\">
            <a class=\"nav-link text-uppercase\" href=\"/contact\">Contact</a>
          </li>
          <li class=\"nav-item dropdown\">
            <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown\" role=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
              Language
          </a>
          <div class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown\">
            <a class=\"dropdown-item\" href=\"/language?lang=en\">English</a>
            <a class=\"dropdown-item\" href=\"/language?lang=pl\">Polska</a>
          </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>
<div class=\"container-fluid\">
  ";
        // line 49
        $this->displayBlock('hero', $context, $blocks);
        // line 51
        echo "  </div>
  <div class=\"container page-body\">
      ";
        // line 53
        $this->displayBlock('container', $context, $blocks);
        // line 55
        echo "  </div>
  <footer class=\"page-footer\">
    <div class=\"container-fluid\">
      <div class=\"row\">
        <div class=\"container\">
          <section class=\"row upper-footer\">
            <div class=\"col-sm-12 col-md-6 col-lg-3 footer-section\">
              <p class=\"footer-heading font-weight-bold text-uppercase\">qCredit</p>
              <nav class=\"navigation footer-navigation\">
                <ul class=\"list-unstyled\">
                  <li><a href=\"/\">Home</a></li>
                  <li><a href=\"/about\">About</a></li>
                  <li><a href=\"/contact\">Contact</a></li>
                  <li><a href=\"/terms\">Terms & Conditions</a></li>
                  <li><a href=\"/application\">Apply for a loan</a></li>
                </ul>
              </nav>
            </div>
            <div class=\"col-sm-12 col-md-6 col-lg-3 footer-section\">
              <p class=\"footer-heading font-weight-bold text-uppercase\">Contact</p>
              <p class=\"footer-contacts\">
                <span><strong>E-mail:</strong> <a href=\"mailto:info@qcredit.pl\">info@qcredit.pl</a></span>
                <span><strong>Address:</strong> Lorem ipsum 28, 115 50,
                Tallinn, Estonia</span>
                <span><strong>Phone:</strong> <a href=\"tel:+4834311254\">+4834311254</a></span>
              </p>
            </div>
          </section>
        </div>
      </div>
      <section class=\"row lower-footer text-center\">
        <a class=\"navbar-brand\" href=\"#\"><img src=\"/src/images/qcredit_logo.png\" alt=\"logo\"></a>
        <p>Copyright © 2018 Brokers - Lorem ipsum 28, 115 50, Tallinn, Estonia</p>
      </section>
    </div>
  </footer>

<link href=\"https://fonts.googleapis.com/css?family=Lato:400,700|Playfair+Display:700\" rel=\"stylesheet\">
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src=\"https://code.jquery.com/jquery-3.3.1.min.js\" integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script><script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\" integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"></script>
<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"></script>
<script src=\"/src/scripts/rangeslider.min.js\"></script>
<script src=\"/src/scripts/functions.js\"></script>
<script src=\"/src/scripts/validation.js\"></script>
";
        // line 100
        if (        $this->hasBlock("endBody", $context, $blocks)) {
            // line 101
            echo "  ";
            $this->displayBlock("endBody", $context, $blocks);
            echo "
";
        }
        // line 103
        echo "</body>
</html>
";
    }

    // line 49
    public function block_hero($context, array $blocks = array())
    {
        // line 50
        echo "  ";
    }

    // line 53
    public function block_container($context, array $blocks = array())
    {
        // line 54
        echo "      ";
    }

    public function getTemplateName()
    {
        return "base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  157 => 54,  154 => 53,  150 => 50,  147 => 49,  141 => 103,  135 => 101,  133 => 100,  86 => 55,  84 => 53,  80 => 51,  78 => 49,  38 => 12,  25 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "base.twig", "/app/templates/base.twig");
    }
}
