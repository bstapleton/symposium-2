My original Symposium project was started and largely abandoned in 2020 during the first covid lockdown.

After spending a bit of time with it in October 2024, I decided I was trying to do too much at once with it - specifically around learning new things, and that was probably why it got abandoned in the first place (along with eventually being allowed to go outside again, anyway).

With this version, rather than trying to relearn a bunch of front-end and design things that I've all but forgotten over the last four years, I've decided to leverage some of the newer Laravel packages to offload that work for me, without me needing to strictly create an API and a separate front-end - though I do have those kinds of projects hanging around if that's what you're into.

## So what is it?

Simple message board, a la mid-2000s. The complexity comes with revision data, and I'm still sort of working it all out.

Basically, any post can be revised infinitely, but replies are mapped to a revision ID _as well as_ the post itself, which should prevent retroactively changing content to make one or more replies look like some kind of fool. Not that I have ever encountered this usage before, but it's a potential problem, so I wanted to come up with a potential solution. Mostly for fun, of course.
