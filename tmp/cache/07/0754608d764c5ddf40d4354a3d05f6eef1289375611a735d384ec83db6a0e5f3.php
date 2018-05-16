<?php

/* admin/users.twig */
class __TwigTemplate_d3684d47c442e8825d27490f6118d8417b4b771e62664523e96b8bba2f80149c extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/users.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'container' => array($this, 'block_container'),
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
        echo "Users";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "      ";
        if (($context["flash"] ?? null)) {
            // line 5
            echo "        <div class=\"row\">
          ";
            // line 6
            if (twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "success", array())) {
                // line 7
                echo "              <div class=\"alert alert-success\" role=\"alert\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "success", array()), "html", null, true);
                echo "</div>
          ";
            } elseif (twig_get_attribute($this->env, $this->source,             // line 8
($context["flash"] ?? null), "error", array())) {
                // line 9
                echo "              <div class=\"alert alert-danger\" role=\"alert\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["flash"] ?? null), "error", array()), "html", null, true);
                echo "</div>
          ";
            }
            // line 11
            echo "        </div>
    ";
        }
        // line 13
        echo "      <h3>Users</h3>
      <table class=\"table\">
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Created</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          ";
        // line 24
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["users"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["user"]) {
            // line 25
            echo "            <tr>
              <td>";
            // line 26
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "id", array()), "html", null, true);
            echo "</td>
              <td>";
            // line 27
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "email", array()), "html", null, true);
            echo "</td>
              <td>";
            // line 28
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "createdAt", array()), "d/m/Y"), "html", null, true);
            echo "</td>
              <td><small><a href=\"/admin/users/delete/";
            // line 29
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["user"], "id", array()), "html", null, true);
            echo "\">Delete</a></small></td>
            </tr>
          ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "        </tbody>
      </table>
      <a href=\"/admin/users/new\" class=\"btn broker-btn broker-btn-blue\">Add new</a>
";
    }

    public function getTemplateName()
    {
        return "admin/users.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  108 => 32,  99 => 29,  95 => 28,  91 => 27,  87 => 26,  84 => 25,  80 => 24,  67 => 13,  63 => 11,  57 => 9,  55 => 8,  50 => 7,  48 => 6,  45 => 5,  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/users.twig", "/app/templates/admin/users.twig");
    }
}
