<?php

class OpenDocument_Content_Body_Text extends DOMElement
{
    
}



class OpenDocument_Content_Element extends DOMElement
{
    public function setStyle(DOMElement $style)
    {
        if (!$style instanceof OpenDocument_Styles_Styles_Style) {
            /* TODO */ trigger_error('DOMElement conversion to OpenDocument_Styles_Styles_Style not implemented', E_USER_WARNING);
        }

    }

    public function getStyle()
    {
        if (!$name = $this->hasAttribute($this->prefix .'style-name')) {
            return null;
        }  
        /* DEBUG */ assert($this->ownerDocument instanceof OpenDocument_Content);
        $style = $this->ownerDocument->getAutomaticStyles()->getStyle($name);
        if (null === $style) {
            $style = $this->ownerDocument->getOwnerPackage()->getStyles()->getStyle($name);
            if (null === $style) {
                $style = $this->ownerDocument->getAutomaticStyles()->createStyle($name);
            }
        }
        /* DEBUG */ assert($style instanceof OpenDocument_Styles_Styles_Style);
        return $style;
    }
}

function cycle($i = 0, $start_position = null, $end_position)
{
    if ($i < $count) {
        if (!is_valid($item[$i])) {
            if (null === $start_position) { // группа еще не создана
                $start_position = $i; // создает группу
            }
            $end_position = $i;
        } else {
            // если группа невалидных узлов не пустая — оборачивает и распускает группу
            if (null !== $start_position) {
                // оборачивает узлы от $start_pos до $end_pos и добавляет в результирующее дерево
                $start_position = null; // распускает группу
            }
            // копирует в результирующее дерево валидный узел
        }
        return cycle($i++, $start_position, $end_position);
    }
    return null;
}

// Валидация согласно схеме RelaxNG
$cur = $item[$i];
$par = $cur->parentNode;

$rules = $schema->query("//define/element[@name='$cur->nodeName']")->item(0);


$element = $content->query('//text:p')->item(0);

$style = $element->getStyle(); // Ищет стиль в автоматических стилях в <office:document-content>.
// Если не находит — ищет в автоматических стилях в <office:document-styles>,
// если не находит — ищет обычных стилях в <office:document-styles>.
// Если нигде нет — создает новый стиль в автоматических стилях в <office:document-content>.
assert($style instanceof OpenDocument_Styles_Elements_Style);

$style->setProperties($props); // Валидные аттрибуты установятся, невалидные отбросятся.