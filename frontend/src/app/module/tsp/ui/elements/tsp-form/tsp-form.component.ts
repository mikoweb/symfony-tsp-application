import { Component, ElementRef, OnInit } from '@angular/core';
import CustomElementBaseComponent from '@app/core/application/custom-element/custom-element-base-component';
import GlobalStyleLoader from '@app/core/application/custom-element/global-style-loader';
import { CustomElement, customElementParams } from '@app/core/application/custom-element/custom-element';
import { IonicModule } from '@ionic/angular';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { GoogleMap } from '@angular/google-maps';
import { FindLocationsQuery } from '@app/module/tsp/application/interaction/query/find-locations-query';
import { SelectLocationDto } from '@app/module/tsp/ui/dto/select-location-dto';
import { catchError, distinctUntilChanged, from, Observable, of, Subject, switchMap, tap } from 'rxjs';
import { NgSelectModule } from '@ng-select/ng-select';
import { AsyncPipe, JsonPipe } from '@angular/common';

const { encapsulation, schemas } = customElementParams;

@Component({
  selector: TspFormComponent.ngSelectorName,
  templateUrl: './tsp-form.component.html',
  styleUrls: ['./tsp-form.component.scss'],
  standalone: true,
  encapsulation,
  schemas,
  imports: [
    IonicModule,
    ReactiveFormsModule,
    TranslateModule,
    GoogleMap,
    NgSelectModule,
    AsyncPipe,
    FormsModule,
    JsonPipe,
  ],
})
@CustomElement()
export class TspFormComponent extends CustomElementBaseComponent implements OnInit {
  public static override readonly customElementName: string = 'app-tsp-form';
  public static override readonly ngSelectorName: string
    = `${CustomElementBaseComponent.ngPrefix}-${TspFormComponent.customElementName}`;

  protected locationsForSelect: Observable<SelectLocationDto[]> = of([]);
  protected selectedLocations: SelectLocationDto[] = [];
  protected locationsLoading: boolean = false;
  protected locationInput = new Subject<string>();

  constructor(
    ele: ElementRef,
    gsl: GlobalStyleLoader,
    private findLocationsQuery: FindLocationsQuery
  ) {
    super(ele, gsl);
  }

  protected override get useGlobalStyle(): boolean {
    return true;
  }

  ngOnInit(): void {
    this.loadLocations();
  }

  protected selectLocation(location: SelectLocationDto): string {
    return location.id;
  }

  private loadLocations(): void {
    this.locationsForSelect = this.locationInput.pipe(
      distinctUntilChanged(),
      tap(() => this.locationsLoading = true),
      switchMap((term) => {
        return from(this.findLocationsQuery.findLocations(term)).pipe(
          catchError(() => of([])),
          tap(() => this.locationsLoading = false)
        );
      })
    );
  }
}
