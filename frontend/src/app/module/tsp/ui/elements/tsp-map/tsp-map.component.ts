import { Component, ElementRef, OnInit } from '@angular/core';
import CustomElementBaseComponent from '@app/core/application/custom-element/custom-element-base-component';
import GlobalStyleLoader from '@app/core/application/custom-element/global-style-loader';
import { CustomElement, customElementParams } from '@app/core/application/custom-element/custom-element';
import { IonicModule } from '@ionic/angular';
import { MobxAngularModule } from 'mobx-angular';
import { DecimalPipe, NgForOf, NgIf } from '@angular/common';
import { SolutionResultStore } from '@app/module/tsp/domain/store/solution-result-store';
import { MapsLoader } from '@app/module/tsp/infrastructure/google/maps-loader';
import { GoogleMap, MapMarker, MapPolyline } from '@angular/google-maps';
import { autorun } from 'mobx';
import { Location } from '@app/module/tsp/domain/vo/location';

const { encapsulation, schemas } = customElementParams;

@Component({
  selector: TspMapComponent.ngSelectorName,
  templateUrl: './tsp-map.component.html',
  styleUrls: ['./tsp-map.component.scss'],
  standalone: true,
  encapsulation,
  schemas,
  imports: [
    IonicModule,
    MobxAngularModule,
    NgIf,
    NgForOf,
    GoogleMap,
    DecimalPipe,
    MapMarker,
    MapPolyline,
  ],
})
@CustomElement()
export class TspMapComponent extends CustomElementBaseComponent implements OnInit {
  public static override readonly customElementName: string = 'app-tsp-map';
  public static override readonly ngSelectorName: string
    = `${CustomElementBaseComponent.ngPrefix}-${TspMapComponent.customElementName}`;

  protected apiMapsLoaded: boolean = false;
  protected zoom: number = 6;
  protected center: any = {lat: 52.90700093005, lng: 18.65257200193756};
  protected markers: any[] = [];
  protected markerOptions: any[] = [];
  protected path: any[] = [];

  constructor(
    ele: ElementRef,
    gsl: GlobalStyleLoader,
    protected store: SolutionResultStore
  ) {
    super(ele, gsl);
  }

  protected override get useGlobalStyle(): boolean {
    return true;
  }

  ngOnInit(): void {
    autorun(() => {
      if (this.store.path.length > 0) {
        const middleLocation: Location = this.store.path[Math.floor(this.store.path.length / 2)];
        this.center = {lat: middleLocation.lat, lng: middleLocation.lng};

        this.markers = [];
        this.markerOptions = [];
        this.path = [];

        for (let [index, location] of this.store.path.slice(0, -1).entries()) {
          this.markers.push({lat: location.lat, lng: location.lng});
          this.markerOptions.push({label: {
              text: `${index + 1}. ${location.name}`,
              fontWeight: 'bold',
              fontSize: '14px',
          }});
        }

        for (let location of this.store.path) {
          this.path.push({lat: location.lat, lng: location.lng});
        }
      }
    });

    MapsLoader.load().then(() => {
      this.apiMapsLoaded = true;
    });
  }
}
