
import { ComponentCustomProperties } from 'vue';

declare module '@vue/runtime-core' {
  // Declare your custom global properties
  interface ComponentCustomProperties {
    $appName: string;
  }
}
