<x-mail::message>

# Welcome {{ $name }}!!

<x-mail::button :url="'https://google.com'">
Button Text
</x-mail::button>

<x-mail::panel>
This is the panel content.
</x-mail::panel>

<x-mail::table>
| Laravel       | Table         | Example       |
| ------------- | :-----------: | ------------: |
| Col 2 is      | Centered      | $10           |
| Col 3 is      | Right-Aligned | $20           |
</x-mail::table>

</x-mail::message>
