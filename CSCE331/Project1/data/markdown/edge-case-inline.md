# Inline formatting edge cases

## Bold

**bold text**
__also bold__
**bold with spaces**
**bold**in**middle**
**multiple** **bold** **words**
****empty bold****
**bold with *italic* inside**

## Italic

*italic text*
_also italic_
*italic with spaces*
*italic*in*middle*
*multiple* *italic* *words*
**empty italic**
*italic with **bold** inside*

## Underline

<ins>underlined text</ins>
<ins>underline with spaces</ins>
<ins></ins>
<ins>underline with **bold** inside</ins>

## Links

[Simple link](https://example.com)
[Link with spaces](https://example.com/path with spaces)
[](https://empty-text.com)
[empty url]()
[Link with **bold**](https://example.com)
Multiple [link1](url1) and [link2](url2) on same line

## Images

![Alt text](https://example.com/image.jpg)
![](https://no-alt.com/image.png)
![Alt only]()
![Image with **bold** alt](https://example.com/img.png)

## Mixed inline formatting

This is **bold** and *italic* and <ins>underlined</ins> text.
Here is a [link](https://example.com) with **bold text** nearby.
**Bold with _nested italic_ inside**
*Italic with __nested bold__ inside*

## Special characters in formatting

**bold with <script> tag**
*italic with & ampersand*
<ins>underline with "quotes"</ins>
[link with <>&](https://example.com?a=1&b=2)

## Edge cases - unclosed/malformed

**unclosed bold
*unclosed italic
<ins>unclosed underline
[unclosed link](
[link with no closing paren](https://example.com
**bold with
newline**

## Nested and overlapping

**bold *italic* bold**
*italic **bold** italic*
**_bold and italic_**
***bold and italic***
___bold and italic___
