import { Component, ElementRef, OnInit } from '@angular/core';
import CustomElementBaseComponent from '@app/core/application/custom-element/custom-element-base-component';
import GlobalStyleLoader from '@app/core/application/custom-element/global-style-loader';
import { CustomElement, customElementParams } from '@app/core/application/custom-element/custom-element';
import { IonicModule } from '@ionic/angular';
import { FormControl, FormGroup, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { TranslateModule } from '@ngx-translate/core';
import { GoogleMap } from '@angular/google-maps';
import { FindLocationsQuery } from '@app/module/tsp/application/interaction/query/find-locations-query';
import { SelectLocationDto } from '@app/module/tsp/ui/dto/select-location-dto';
import { catchError, distinctUntilChanged, from, Observable, of, Subject, switchMap, tap } from 'rxjs';
import { NgSelectModule } from '@ng-select/ng-select';
import { AsyncPipe, NgForOf } from '@angular/common';
import { SolveRequestDto } from '@app/module/tsp/ui/dto/solve-request-dto';
import { LocationDto } from '@app/module/tsp/ui/dto/location-dto';
import CommandBus from '@app/core/application/command-bus/command-bus';
import {
  SolveProblemCommand
} from '@app/module/tsp/application/interaction/command/solve-problem/solve-problem-command';
import { MatTooltipModule } from '@angular/material/tooltip';
import { GetDefaultParametersQuery } from '@app/module/tsp/application/interaction/query/get-default-parameters-query';
import { DefaultParametersDto } from '@app/module/tsp/ui/dto/default-parameters-dto';

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
    NgForOf,
    MatTooltipModule,
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

  protected readonly form: FormGroup = new FormGroup({
    iterations: new FormControl(),
    initialLocationIndex: new FormControl(),
    alpha: new FormControl(),
    beta: new FormControl(),
    distanceCoefficient: new FormControl(),
    evaporation: new FormControl(),
    antFactor: new FormControl(),
    c: new FormControl(),
  });

  constructor(
    ele: ElementRef,
    gsl: GlobalStyleLoader,
    private findLocationsQuery: FindLocationsQuery,
    private commandBus: CommandBus,
    private getDefaultParametersQuery: GetDefaultParametersQuery
  ) {
    super(ele, gsl);
  }

  protected override get useGlobalStyle(): boolean {
    return true;
  }

  async ngOnInit(): Promise<void> {
    this.initFormValues();
    this.loadLocations();
  }

  protected async initFormValues(): Promise<void> {
    this.form.get('initialLocationIndex')?.setValue(0);

    const defaultParameters: DefaultParametersDto = await this.getDefaultParametersQuery.getDefaultParameters();

    this.form.get('iterations')?.setValue(defaultParameters.iterations);
    this.form.get('alpha')?.setValue(defaultParameters.alpha);
    this.form.get('beta')?.setValue(defaultParameters.beta);
    this.form.get('distanceCoefficient')?.setValue(defaultParameters.distanceCoefficient);
    this.form.get('evaporation')?.setValue(defaultParameters.evaporation);
    this.form.get('antFactor')?.setValue(defaultParameters.antFactor);
    this.form.get('c')?.setValue(defaultParameters.c);
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
        }),
        this.form.get('iterations')?.value,
        this.form.get('initialLocationIndex')?.value,
        this.form.get('alpha')?.value,
        this.form.get('beta')?.value,
        this.form.get('distanceCoefficient')?.value,
        this.form.get('evaporation')?.value,
        this.form.get('antFactor')?.value,
        this.form.get('c')?.value,
      );

      await this.commandBus.execute(new SolveProblemCommand(solveRequest));
      this.formDisabled = false;
    }
  }

  protected isFilled(): boolean {
    return this.selectedLocations.length > 0 && this.form.valid;
  }

  protected selectLocation(location: SelectLocationDto): string {
    return location.id;
  }

  protected onRemoveLocation(): void {
    this.form.get('initialLocationIndex')?.setValue(0);
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
