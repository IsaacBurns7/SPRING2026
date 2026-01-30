# Paragraph edge cases

## Multiple blank lines

Paragraph one.


Paragraph two (with double blank line above).



Paragraph three (with triple blank line above).

## Paragraphs with special characters

Paragraph with <script>alert('xss')</script> tag.

Paragraph with & ampersand & multiple & ampersands.

Paragraph with "double quotes" and 'single quotes'.

Paragraph with < > characters.

## Long paragraphs

This is a very long paragraph that contains a lot of text to test how the markdown processor handles longer content. It should be rendered as a single paragraph even though it contains many words. The processor should wrap this appropriately without creating multiple paragraphs unless there is a blank line.

## Paragraphs with tabs and spaces

Paragraph with    multiple    spaces.

	Paragraph with leading tab.
  Paragraph with leading spaces.

## Empty lines between content

# Heading


Paragraph after heading with blank line.


Another paragraph.

## Only whitespace

   
	
     

Should this be handled?
