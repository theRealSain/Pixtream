import { library, dom } from '@fortawesome/fontawesome-svg-core';

// Import all icons from each free icon package
import * as solidIcons from '@fortawesome/free-solid-svg-icons';
import * as regularIcons from '@fortawesome/free-regular-svg-icons';
import * as brandIcons from '@fortawesome/free-brands-svg-icons';

// Add all icons to the library
library.add(
  ...Object.values(solidIcons),
  ...Object.values(regularIcons),
  ...Object.values(brandIcons)
);

dom.watch(); // Replace any <i> tags in your HTML with <svg>
