<?php

/* admin/partners.twig */
class __TwigTemplate_64cbc685de400867a241accd86d28c4d162058cbeb1c80fd71a20e1ccc10f85f extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/partners.twig", 1);
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
        echo "All Partners";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "    <h3>Partners</h3>
    <table class=\"table\">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Commission</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["partners"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["partner"]) {
            // line 16
            echo "        <tr>
          <td>";
            // line 17
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "id", array()), "html", null, true);
            echo "</td>
          <td>";
            // line 18
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "name", array()), "html", null, true);
            echo "</td>
          <td>";
            // line 19
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "commission", array()), "html", null, true);
            echo "</td>
          <td class=\"text-right\"><a href=\"/admin/partners/";
            // line 20
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "id", array()), "html", null, true);
            echo "\">Details</a></td>
        </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['partner'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "      </tbody>
    </table>
    <p><a class=\"btn broker-btn broker-btn-blue\" href=\"/admin/partners/new\">Add new partner</a></p>
";
    }

    public function getTemplateName()
    {
        return "admin/partners.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  83 => 23,  74 => 20,  70 => 19,  66 => 18,  62 => 17,  59 => 16,  55 => 15,  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/partners.twig", "/app/templates/admin/partners.twig");
    }
}
