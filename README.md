> [!WARNING]
> #### Plugin Advisory: End of Support on 30.11.2026
> With new details on Unraid 8 now available, it felt like the right time to make a final decision about this plugin and set clear expectations. I've been focused on new things for a while and am preparing the transition of my own infrastructure, so I can no longer give the plugin the attention and love it deserves. It wasn't an easy choice, but support will end on 30.11.2026. From that date, the plugin stays installable as-is, but without any guarantee of stability or usability, so use is entirely at your own risk. The repository will be archived, but if a future Unraid release is reported to break the plugin in the support thread, I'll have to cap the maximum supported version and prevent further installs from that version onwards. Forks are very welcome; just coordinate with the Community Applications maintainers. Thank you all for the support over the years - Unraid has been an amazing community to serve, and I hope to stay in touch with many of you on the forums or Discord.

SnapRAID on UNRAID
================
_A plugin for advanced users installing SnapRAID onto UNRAID systems._

![GitHub Tag](https://img.shields.io/github/v/tag/desertwitch/SnapRAID-on-unRAID?label=release&color=peru)
[![CodeFactor](https://www.codefactor.io/repository/github/desertwitch/snapraid-on-unraid/badge)](https://www.codefactor.io/repository/github/desertwitch/snapraid-on-unraid)

#### Possible Use-Cases:

- Non real-time (snapshot) parity, corruption detection/repair and undelete for unassigned / non-array disks.
_An example could be the parity protection of a custom-mounted, mergerFS-pooled array of disks outside of Unraid's._

- Non real-time (snapshot) corruption detection/repair and undelete for array disks (on top of Unraid parity).
_An example could be comparing rarely changing media against earlier snapshot to detect and fix unwanted changes._


#### Known Limitations:

- Parity has minor space overhead, works best on large and rarely changing files (such as a media library).
- Not possible to use Unraid parity disk as SnapRAID parity disk (as SnapRAID operates on the file-level).
- A data disk at least size of the largest to be protected disk (in the SnapRAID array) is needed for parity.
- Most of my testing was on XFS filesystems, so other filesystems should be considered more experimental.
- ... for more information see: https://www.snapraid.it/manual

#### Install from URL
https://raw.githubusercontent.com/desertwitch/SnapRAID-on-unRAID/main/plugin/dwsnap.plg

<img src="https://github.com/desertwitch/SnapRAID-on-unRAID/assets/24509509/d39a9014-5290-411c-bccf-3f90e6b18423" width="600px">

#### Install from Community Applications
This plugin is installable via Community Applications.

#### License
Plugin licensed under GPL2, GPL3 and/or MIT (where applicable, see respective source code files).

#### Credits
- SnapRAID © 2011-2024 Andrea Mazzoleni (https://github.com/amadvance/snapraid)
- SnapRAID AIO Script © Oliver Cervera (https://github.com/auanasgheps/snapraid-aio-script)

<sub>SnapRAID Source Code: https://github.com/amadvance/snapraid</sub>
