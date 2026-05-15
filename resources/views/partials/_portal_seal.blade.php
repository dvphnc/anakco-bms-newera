{{--
    Official Barangay New Era Seal — SVG
    Scalable: used in navbar (42px), footer (48px)
--}}
<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Official Seal of Barangay New Era">
    <!-- Outer navy circle -->
    <circle cx="50" cy="50" r="49" fill="#0D2144"/>
    <!-- Gold outer ring -->
    <circle cx="50" cy="50" r="47" fill="none" stroke="#C8861A" stroke-width="2"/>
    <!-- Text band (between r=47 and r=36) -->

    <!-- Top arc text path: from left (3,50) clockwise through top to right (97,50) -->
    <defs>
        <path id="bne-arc-top" d="M3,50 A47,47,0,1,1,97,50" fill="none"/>
        <path id="bne-arc-bot" d="M97,50 A47,47,0,0,1,3,50" fill="none"/>
    </defs>

    <!-- "BARANGAY NEW ERA" along the top arc -->
    <text font-size="9.5" fill="white" font-family="Georgia,Times New Roman,serif" font-weight="bold" letter-spacing="2.2">
        <textPath href="#bne-arc-top" startOffset="6%">BARANGAY NEW ERA</textPath>
    </text>

    <!-- "DISTRICT VI • QUEZON CITY" along the bottom arc -->
    <text font-size="7.5" fill="rgba(255,255,255,0.78)" font-family="Georgia,Times New Roman,serif" letter-spacing="1.5">
        <textPath href="#bne-arc-bot" startOffset="8%">DISTRICT VI • QUEZON CITY</textPath>
    </text>

    <!-- Gold separator rings -->
    <circle cx="50" cy="50" r="35" fill="none" stroke="#C8861A" stroke-width="1" opacity="0.7"/>

    <!-- White inner circle -->
    <circle cx="50" cy="50" r="33" fill="white"/>

    <!-- ─── Barangay Hall Building ─── -->
    <!-- Pediment (triangular roof) -->
    <polygon points="50,19 66,31 34,31" fill="#0D2144"/>
    <!-- Gold frieze bar -->
    <rect x="34" y="31" width="32" height="3.5" fill="#C8861A"/>
    <!-- 5 columns -->
    <rect x="36"   y="34.5" width="4" height="17" fill="#0D2144" rx="1.5"/>
    <rect x="42.5" y="34.5" width="4" height="17" fill="#0D2144" rx="1.5"/>
    <rect x="49"   y="34.5" width="4" height="17" fill="#0D2144" rx="1.5"/>
    <rect x="55.5" y="34.5" width="4" height="17" fill="#0D2144" rx="1.5"/>
    <rect x="62"   y="34.5" width="4" height="17" fill="#0D2144" rx="1.5"/>
    <!-- Door (gold) -->
    <rect x="47" y="43" width="8" height="8.5" fill="#C8861A" rx="1.5"/>
    <!-- Steps -->
    <rect x="33" y="51.5" width="36" height="3"   fill="#C8861A"/>
    <rect x="31" y="54.5" width="40" height="2.5" fill="#C8861A" opacity="0.65"/>
    <!-- Star in pediment -->
    <polygon points="50,21.5 51.4,25.8 56,25.8 52.3,28.4 53.7,32.7 50,30.1 46.3,32.7 47.7,28.4 44,25.8 48.6,25.8"
             fill="#C8861A"/>

    <!-- Small decorative dots at 3 and 9 o'clock on the text ring -->
    <circle cx="3"  cy="50" r="2.5" fill="#C8861A"/>
    <circle cx="97" cy="50" r="2.5" fill="#C8861A"/>

    <!-- 4 small stars at cardinal positions on gold ring (r=47) -->
    <!-- Top -->
    <polygon points="50,4 50.8,6.5 53.5,6.5 51.3,8 52.1,10.5 50,9 47.9,10.5 48.7,8 46.5,6.5 49.2,6.5" fill="#C8861A" opacity="0.85"/>
    <!-- Bottom -->
    <polygon points="50,96 50.8,93.5 53.5,93.5 51.3,92 52.1,89.5 50,91 47.9,89.5 48.7,92 46.5,93.5 49.2,93.5" fill="#C8861A" opacity="0.85"/>
</svg>
