<?php

/* admin/index.twig */
class __TwigTemplate_2e6cd595e5bc904e8abb904b70cbf7bc426dc4d7c4a66d8c9b059755dc97b0ce extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/index.twig", 1);
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
        echo "Dashboard";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "<h3>Dashboard</h3>
  <div class=\"container\">
    <div class=\"row loan-stats-wrap\">
      <div class=\"col-sm-12 loan-stats-header\">
        <p class=\"table-lead\">Application data</p>
        <p>
          <span class=\"date-select date-select-day active\">1 day</span>
          <span class=\"date-select date-select-week\">A week</span>
          <span class=\"date-select date-select-month\">A month</span>
        </p>
      </div>
      <div class=\"col-sm-12 loan-stats-body\">
        <div class=\"stat-boxes\">
          <div class=\"stat-box\">
            <div class=\"stat-box-content\">
              <span class=\"amount total day show\">";
        // line 19
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "day", array()), "total", array()), "html", null, true);
        echo "</span><span class=\"amount total week\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "week", array()), "total", array()), "html", null, true);
        echo "</span><span class=\"amount total month\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "month", array()), "total", array()), "html", null, true);
        echo "</span>
              <span class=\"name\">Total apps</span>
            </div>
          </div>
          <div class=\"stat-box\">
            <div class=\"stat-box-content stat-box-content-accepted\">
              <span class=\"amount accepted day show\">";
        // line 25
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "day", array()), "accepted", array()), "html", null, true);
        echo "</span><span class=\"amount accepted week\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "week", array()), "accepted", array()), "html", null, true);
        echo "</span><span class=\"amount accepted month\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "month", array()), "accepted", array()), "html", null, true);
        echo "</span>
              <span class=\"name\">Accepted</span>
            </div>
          </div>
          <div class=\"stat-box\">
            <div class=\"stat-box-content stat-box-content-paid-out\">
              <span class=\"amount paid-out day show\">";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "day", array()), "paidOut", array()), "html", null, true);
        echo "</span><span class=\"amount paid-out week\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "week", array()), "paidOut", array()), "html", null, true);
        echo "</span><span class=\"amount paid-out month\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "month", array()), "paidOut", array()), "html", null, true);
        echo "</span>
              <span class=\"name\">Paid out</span>
            </div>
          </div>
          <div class=\"stat-box\">
            <div class=\"stat-box-content stat-box-content-rejected\">
              <span class=\"amount rejected day show\">";
        // line 37
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "day", array()), "rejected", array()), "html", null, true);
        echo "</span><span class=\"amount rejected week\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "week", array()), "rejected", array()), "html", null, true);
        echo "</span><span class=\"amount  rejected month\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "month", array()), "rejected", array()), "html", null, true);
        echo "</span>
              <span class=\"name\">Rejected</span>
            </div>
          </div>
          <div class=\"stat-box\">
            <div class=\"stat-box-content stat-box-content-in-process\">
              <span class=\"amount in-process day show\">";
        // line 43
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "day", array()), "inProcess", array()), "html", null, true);
        echo "</span><span class=\"amount in-process week\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "week", array()), "inProcess", array()), "html", null, true);
        echo "</span><span class=\"amount in-process month\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["stats"] ?? null), "month", array()), "inProcess", array()), "html", null, true);
        echo "</span>
              <span class=\"name\">In process</span>
            </div>
          </div>
        </div>
        <div class=\"stats-line\">
          <span class=\"stats-line-item stats-accepted\" id=\"stats-accepted\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Accepted\"></span><span class=\"stats-line-item stats-paid-out\" id=\"stats-paid-out\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Paid out\"></span><span class=\"stats-line-item stats-rejected\" id=\"stats-rejected\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Rejected\"></span><span class=\"stats-line-item stats-in-process\" id=\"stats-in-process\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"In process\"></span>
        </div>
      </div>
    </div>
    <table class=\"table\">
      <caption>Partners data</caption>
      <thead>
        <tr>
          <th></th>
          <th>Applications</th>
          <th>Accepted</th>
          <th>Paid out</th>
          <th>Rejected</th>
          <th>In Progress</th>
        </tr>
      </thead>
      <tbody>
        ";
        // line 66
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["partners"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["partner"]) {
            // line 67
            echo "          <tr>
            <td>";
            // line 68
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "name", array()), "html", null, true);
            echo "</td>
            <td>";
            // line 69
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "total", array()), "html", null, true);
            echo "</td>
            <td>";
            // line 70
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "accepted", array()), "html", null, true);
            echo "</td>
            <td>";
            // line 71
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "paidOut", array()), "html", null, true);
            echo "</td>
            <td>";
            // line 72
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "rejected", array()), "html", null, true);
            echo "</td>
            <td>";
            // line 73
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["partner"], "inProcess", array()), "html", null, true);
            echo "</td>
          </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['partner'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 76
        echo "      </tbody>
    </table>
  </div>
";
    }

    public function getTemplateName()
    {
        return "admin/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  177 => 76,  168 => 73,  164 => 72,  160 => 71,  156 => 70,  152 => 69,  148 => 68,  145 => 67,  141 => 66,  111 => 43,  98 => 37,  85 => 31,  72 => 25,  59 => 19,  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/index.twig", "/app/templates/admin/index.twig");
    }
}
