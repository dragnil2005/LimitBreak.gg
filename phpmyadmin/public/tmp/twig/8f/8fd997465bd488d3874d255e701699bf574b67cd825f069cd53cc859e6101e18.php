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

/* table/maintenance/analyze.twig */
class __TwigTemplate_8239f43806c3c10ce6daef1b3a0ea5185700d01788cc4b71be31c418aa8a0f60 extends Template
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
echo _gettext("Analyze table");
        // line 4
        echo "    ";
        echo PhpMyAdmin\Html\MySQLDocumentation::show("ANALYZE_TABLE");
        echo "
  </h2>

  ";
        // line 7
        echo ($context["message"] ?? null);
        echo "

  ";
        // line 9
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["rows"] ?? null));
        foreach ($context['_seq'] as $context["name"] => $context["table"]) {
            // line 10
            echo "    <div class=\"card mb-3\">
      <div class=\"card-header\">";
            // line 11
            echo twig_escape_filter($this->env, $context["name"], "html", null, true);
            echo "</div>

      <ul class=\"list-group list-group-flush\">
        ";
            // line 14
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["table"]);
            foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                // line 15
                echo "          <li class=\"list-group-item\">
            ";
                // line 16
                if ((0 !== twig_compare(twig_lower_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "operation", [], "any", false, false, false, 16)), "analyze"))) {
                    // line 17
                    echo "              <span class=\"badge bg-secondary text-dark\">";
                    echo twig_escape_filter($this->env, twig_title_string_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "operation", [], "any", false, false, false, 17)), "html", null, true);
                    echo "</span>
            ";
                }
                // line 19
                echo "
            ";
                // line 20
                ob_start(function () { return ''; });
                // line 21
                if ((0 === twig_compare(twig_lower_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "type", [], "any", false, false, false, 21)), "error"))) {
                    // line 22
                    echo "bg-danger";
                } elseif ((0 === twig_compare(twig_lower_filter($this->env, twig_get_attribute($this->env, $this->source,                 // line 23
$context["row"], "type", [], "any", false, false, false, 23)), "warning"))) {
                    // line 24
                    echo "bg-warning";
                } elseif (((0 === twig_compare(twig_lower_filter($this->env, twig_get_attribute($this->env, $this->source,                 // line 25
$context["row"], "type", [], "any", false, false, false, 25)), "info")) || (0 === twig_compare(twig_lower_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "type", [], "any", false, false, false, 25)), "note")))) {
                    // line 26
                    echo "bg-info";
                } else {
                    // line 28
                    echo "bg-secondary";
                }
                $context["badge_variation"] = ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
                // line 31
                echo "            <span class=\"badge ";
                echo twig_escape_filter($this->env, ($context["badge_variation"] ?? null), "html", null, true);
                echo " text-dark\">";
                echo twig_escape_filter($this->env, twig_title_string_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "type", [], "any", false, false, false, 31)), "html", null, true);
                echo "</span>

            ";
                // line 33
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["row"], "text", [], "any", false, false, false, 33), "html", null, true);
                echo "
          </li>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 36
            echo "      </ul>
    </div>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['table'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 39
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "table/maintenance/analyze.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  129 => 39,  121 => 36,  112 => 33,  104 => 31,  100 => 28,  97 => 26,  95 => 25,  93 => 24,  91 => 23,  89 => 22,  87 => 21,  85 => 20,  82 => 19,  76 => 17,  74 => 16,  71 => 15,  67 => 14,  61 => 11,  58 => 10,  54 => 9,  49 => 7,  42 => 4,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "table/maintenance/analyze.twig", "C:\\OSPanel\\home\\phpmyadmin\\public\\templates\\table\\maintenance\\analyze.twig");
    }
}
