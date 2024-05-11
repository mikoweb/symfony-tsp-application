import { Loader } from '@googlemaps/js-api-loader';
import { environment } from '../../../../../environments/environment';

export class MapsLoader {
  private static loaded: boolean = false;

  public static load(): Promise<boolean> {
    return new Promise<boolean>((resolve, reject) => {
      if (this.loaded) {
        resolve(true);
      } else {
        const loader = new Loader({
          apiKey: environment.googleMapsApiKey,
          version: 'weekly',
        });

        loader.importLibrary('maps')
          .then(() => {
            resolve(true);
          }).catch((error) => {
            reject(error);
          });
      }
    });
  }
}
