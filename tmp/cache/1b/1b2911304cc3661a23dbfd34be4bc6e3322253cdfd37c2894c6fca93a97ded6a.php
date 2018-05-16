<?php

/* admin/applications.twig */
class __TwigTemplate_26d480033ccad8772104aed54c46c7c22701b32fb619bd66280f62bdcfe7b18d extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/applications.twig", 1);
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
        echo "All Applications";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "  <h3>Applications</h3>
  <table class=\"table\">
      <thead>
          <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Amount</th>
              <th>Term</th>
              <th>Status</th>
              <th></th>
          </tr>
      </thead>
      <tbody>
      ";
        // line 17
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["applications"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["app"]) {
            // line 18
            echo "          <tr>
              <th>";
            // line 19
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["app"], "id", array()), "html", null, true);
            echo "</th>
              <td>";
            // line 20
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["app"], "fullName", array()), "html", null, true);
            echo "</td>
              <td>";
            // line 21
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["app"], "loanAmount", array()), "html", null, true);
            echo " PLN</td>
              <td>";
            // line 22
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["app"], "loanTerm", array()), "html", null, true);
            echo "</td>
              <td>";
            // line 23
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["app"], "status", array()), "html", null, true);
            echo "</td>
              <td><a href=\"/admin/applications/";
            // line 24
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["app"], "id", array()), "html", null, true);
            echo "\">Details</a></td>
          </tr>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['app'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "      </tbody>
  </table>
";
    }

    public function getTemplateName()
    {
        return "admin/applications.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  93 => 27,  84 => 24,  80 => 23,  76 => 22,  72 => 21,  68 => 20,  64 => 19,  61 => 18,  57 => 17,  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/applications.twig", "/app/templates/admin/applications.twig");
    }
}
