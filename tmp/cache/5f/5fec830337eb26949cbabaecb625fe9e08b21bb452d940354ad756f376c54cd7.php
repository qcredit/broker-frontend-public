<?php

/* admin.twig */
class __TwigTemplate_c774220c20b334fc94161622fe656a44da4298b370f695f2f3f25089bce8af1d extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
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
    <!-- Bootstrap CSS -->
    <link rel=\"stylesheet\" href=\"//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" integrity=\"sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm\" crossorigin=\"anonymous\">
    <link href=\"https://fonts.googleapis.com/css?family=Lato:400,700\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" href=\"/src/styles/admin.css\">
    <title>";
        // line 12
        $this->displayBlock("title", $context, $blocks);
        echo "</title>
</head>
<body>
  <header class=\"page-header\">
      <nav class=\"navbar navbar-expand-md navbar-light\">
      <a class=\"navbar-brand\" href=\"/admin\"><img class=\"navbar-brand\" src=\"/src/images/crm_broker_logo.png\" alt=\"logo\"></a>
      <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarNav\" aria-controls=\"navbarNav\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
        <span class=\"navbar-toggler-icon\"></span>
      </button>
      <div class=\"collapse navbar-collapse\" id=\"navbarNav\">
        <ul class=\"navbar-nav mr-auto\">
          <li class=\"nav-item\">
            <a class=\"nav-link text-uppercase\" href=\"/admin\">Dashboard <span class=\"sr-only\">(current)</span></a>
          </li>
          <li class=\"nav-item\">
            <a class=\"nav-link text-uppercase\" href=\"/admin/applications\">Applications</a>
          </li>
          <li class=\"nav-item\">
            <a class=\"nav-link text-uppercase\" href=\"/admin/partners\">Partners</a>
          </li>
        </ul>
        <div class=\"login-info my-2 my-lg-0\">
         <span><strong>";
        // line 34
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", array()), "html", null, true);
        echo "</strong></span>
         ";
        // line 35
        if (twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", array())) {
            echo "<a class=\"nav-logout\" href=\"/admin/logout\" onclick=\"signOut();\">Logout</a>";
        }
        // line 36
        echo "       </div>
      </div>
    </nav>
  </header>
  <div class=\"container\">
      ";
        // line 41
        $this->displayBlock('container', $context, $blocks);
        // line 43
        echo "  </div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src=\"https://code.jquery.com/jquery-3.2.1.slim.min.js\" integrity=\"sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN\" crossorigin=\"anonymous\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\" integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"></script>
<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"></script>
<script src=\"/src/scripts/admin.js\"></script>
<script src=\"https://apis.google.com/js/platform.js?onload=initAuth\" async defer></script>
";
        // line 51
        if (        $this->hasBlock("endBody", $context, $blocks)) {
            // line 52
            echo "  ";
            $this->displayBlock("endBody", $context, $blocks);
            echo "
";
        }
        // line 54
        echo "</body>
</html>
";
    }

    // line 41
    public function block_container($context, array $blocks = array())
    {
        // line 42
        echo "      ";
    }

    public function getTemplateName()
    {
        return "admin.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  106 => 42,  103 => 41,  97 => 54,  91 => 52,  89 => 51,  79 => 43,  77 => 41,  70 => 36,  66 => 35,  62 => 34,  37 => 12,  24 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin.twig", "/app/templates/admin.twig");
    }
}
