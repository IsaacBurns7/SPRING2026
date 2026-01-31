# Complex edge cases

## Heading immediately followed by list

# Heading with no blank line

* Item 1
* Item 2

# Another heading

1. Ordered item
2. Another

## Nested lists with headings between

* Item 1
  * Nested 1

# Heading in the middle

    * This should be nested but there's a heading above
  * Still nested

## Multiple heading levels rapidly

# Level 1
## Level 2
### Level 3
#### Level 4
##### Level 5
###### Level 6

Paragraph after headings.

## List transitioning between types

* Unordered 1
* Unordered 2

1. Now ordered 1
2. Now ordered 2

* Back to unordered
* Another unordered

## Deeply nested with transitions

* Item A
  * Item A1
    * Item A1a
      1. Now ordered deep
      2. Still deep ordered
    * Back to unordered
  * Back to level 1 nested
* Back to root

## Content with all special characters

# Heading: <>&"'

Paragraph: <>&"'

* List item: <>&"'
  * Nested: <>&"'

1. Ordered: <>&"'

## Minimal content

#

*

1.

## Whitespace-only list items

*   
  *   

1.   
  1.   
