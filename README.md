SnapRAID on UNRAID
================
_An actively developed plugin for advanced users installing SnapRAID onto UNRAID systems._

#### Possible Use-Cases:

- Non real-time (snapshot) parity, corruption detection/repair and undelete for unassigned / non-array drives.
_An example could be the parity protection of a custom-mounted, mergerFS-pooled array of drives outside of Unraid's._

- Non real-time (snapshot) corruption detection/repair and undelete for array drives (on top of Unraid parity).
_An example could be comparing rarely changing media against earlier snapshot to detect and fix unwanted changes._


#### Known Limitations:

- Parity has minor space overhead, works best on large and rarely changing files (such as a media library).
- Not possible to use Unraid parity drive as SnapRAID parity drive (as SnapRAID operates on the file-level).
- A data drive at least the size of largest to be protected drive (in the SnapRAID array) is needed for parity.
- Most of my testing was on XFS filesystems, so other filesystems should be considered more experimental.
- ... for more see: https://www.snapraid.it/manual

#### Install from URL
https://raw.githubusercontent.com/desertwitch/SnapRAID-on-unRAID/main/plugin/dwsnap.plg

<img src="https://github.com/desertwitch/SnapRAID-on-unRAID/assets/24509509/d39a9014-5290-411c-bccf-3f90e6b18423" width="600px">

#### License
Plugin licensed under GPL2, GPL3 and/or MIT (where applicable, see respective source code files).

SnapRAID © 2011-2024 Andrea Mazzoleni (https://github.com/amadvance/snapraid)
