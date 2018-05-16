<?php

/* admin/login.twig */
class __TwigTemplate_b01dbdda76322e6138deda465921db0700a748fd2be6a7142b9a39d16a80b6cc extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/login.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'container' => array($this, 'block_container'),
            'endBody' => array($this, 'block_endBody'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "Login to continue";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "  <div class=\"row justify-content-sm-center\">
    <div class=\"col-sm-9 col-md-5 login-container\">
      <h4 class=\"login-header\">Log in to continue</h4>
        <div class=\"g-signin2\" data-onsuccess=\"onSignIn\"></div>
        <a href=\"#\" class=\"logout\" onclick=\"signOut();\">Logout</a>
    </div>
  </div>
";
    }

    // line 12
    public function block_endBody($context, array $blocks = array())
    {
        // line 13
        echo "  <script type=\"application/javascript\">
    function onSignIn(googleUser) {
      var profile = googleUser.getBasicProfile();
      var id_token = googleUser.getAuthResponse().id_token;

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/admin/login');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        var response = JSON.parse(xhr.responseText);
        if (response.error)
        {
          console.log(response.error);
        }
        else if (response.success) {
          window.location.href = '/admin';
        }
      };

      var params = {
        'idToken': id_token,
        'email': profile.getEmail(),
        '";
        // line 35
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "name", array()), "html", null, true);
        echo "': '";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "name", array()), "html", null, true);
        echo "',
        '";
        // line 36
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "value", array()), "html", null, true);
        echo "': '";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "value", array()), "html", null, true);
        echo "'
      };

      var urlParams = new URLSearchParams();
      for (var [key, val] of Object.entries(params)) {
        urlParams.append(key, val);
      }

      xhr.send(urlParams.toString());
    }
  </script>
";
    }

    public function getTemplateName()
    {
        return "admin/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  87 => 36,  81 => 35,  57 => 13,  54 => 12,  43 => 4,  40 => 3,  34 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/login.twig", "/app/templates/admin/login.twig");
    }
}
