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
import { SolveRequestDto } from '@app/module/tsp/ui/dto/solve-request-dto';
import { LocationDto } from '@app/module/tsp/ui/dto/location-dto';
import CommandBus from '@app/core/application/command-bus/command-bus';
import {
  SolveProblemCommand
} from '@app/module/tsp/application/interaction/command/solve-problem/solve-problem-command';

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

  protected formDisabled: boolean = false;

  constructor(
    ele: ElementRef,
    gsl: GlobalStyleLoader,
    private findLocationsQuery: FindLocationsQuery,
    private commandBus: CommandBus,
  ) {
    super(ele, gsl);
  }

  protected override get useGlobalStyle(): boolean {
    return true;
  }

  ngOnInit(): void {
    this.loadLocations();
  }

  protected async onSubmit(): Promise<void> {
    await this.submit();
  }

  protected async submit(): Promise<void> {
    if (this.isFilled()) {
      this.formDisabled = true;

      const solveRequest: SolveRequestDto = new SolveRequestDto(
        this.selectedLocations.map((location: SelectLocationDto) => {
          return new LocationDto(location.lat, location.lng, location.name);
        }
      ));

      await this.commandBus.execute(new SolveProblemCommand(solveRequest));
      this.formDisabled = false;
    }
  }

  protected isFilled(): boolean {
    return this.selectedLocations.length > 0;
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
