<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* table/maintenance/checksum.twig */
class __TwigTemplate_e2786f62466e663ada66dde7dd9dd4f4010dc1c339f70ce78b71f0235c29f951 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"container-fluid\">
  <h2>
    ";
echo _gettext("Checksum table");
        // line 4
        echo "    ";
        echo PhpMyAdmin\Html\MySQLDocumentation::show("CHECKSUM_TABLE");
        echo "
  </h2>

  ";
        // line 7
        echo ($context["message"] ?? null);
        echo "

  <table class=\"table table-striped w-auto my-3\">
    <thead>
      <tr>
        <th>";
echo _gettext("Table");
        // line 12
        echo "</th>
        <th>";
echo _gettext("Checksum");
        // line 13
        echo "</th>
      </tr>
    </thead>
    <tbody>
      ";
        // line 17
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["rows"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 18
            echo "        <tr>
          <td>";
            // line 19
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "Table", [], "any", false, false, false, 19), "html", null, true);
            echo "</td>
          <td class=\"text-end\">
            ";
            // line 21
            if ( !(null === twig_get_attribute($this->env, $this->source, $context["row"], "Checksum", [], "any", false, false, false, 21))) {
                // line 22
                echo "              ";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "Checksum", [], "any", false, false, false, 22), "html", null, true);
                echo "
            ";
            } else {
                // line 24
                echo "              <em class=\"text-muted\">NULL</em>
            ";
            }
            // line 26
            echo "          </td>
        </tr>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "    </tbody>
  </table>

  ";
        // line 32
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["warnings"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["warning"]) {
            // line 33
            echo "    ";
            echo $this->env->getFilter('notice')->getCallable()($context["warning"]);
            echo "
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['warning'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "table/maintenance/checksum.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  118 => 35,  109 => 33,  105 => 32,  100 => 29,  92 => 26,  88 => 24,  82 => 22,  80 => 21,  75 => 19,  72 => 18,  68 => 17,  62 => 13,  58 => 12,  49 => 7,  42 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "table/maintenance/checksum.twig", "C:\\OSPanel\\home\\phpmyadmin\\public\\templates\\table\\maintenance\\checksum.twig");
    }
}
