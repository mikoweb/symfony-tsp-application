import { MapGeocoder } from '@angular/google-maps';
import { SelectLocationDto } from '@app/module/tsp/ui/dto/select-location-dto';
import { MapsLoader } from '@app/module/tsp/infrastructure/google/maps-loader';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class FindLocationsQuery {
  constructor(
    private geocoder: MapGeocoder
  ) {}

  public findLocations(name: string): Promise<SelectLocationDto[]> {
    return new Promise<SelectLocationDto[]>(resolve => {
      MapsLoader.load().then(() => {
        this.geocoder.geocode({
          address: name,
        }).subscribe(({results}) => {
          resolve(results.map((result) => {
            return new SelectLocationDto(
              result.place_id,
              result.formatted_address,
              result.geometry.location.lat(),
              result.geometry.location.lng()
            );
          }));
        });
      })
    });
  }
}
