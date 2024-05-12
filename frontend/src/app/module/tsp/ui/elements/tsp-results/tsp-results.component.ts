import { Component, ElementRef } from '@angular/core';
import CustomElementBaseComponent from '@app/core/application/custom-element/custom-element-base-component';
import GlobalStyleLoader from '@app/core/application/custom-element/global-style-loader';
import { CustomElement, customElementParams } from '@app/core/application/custom-element/custom-element';
import { IonicModule } from '@ionic/angular';
import { MobxAngularModule } from 'mobx-angular';
import { DecimalPipe, NgForOf, NgIf } from '@angular/common';
import { TranslateModule } from '@ngx-translate/core';
import { SolutionResultStore } from '@app/module/tsp/domain/store/solution-result-store';

const { encapsulation, schemas } = customElementParams;

@Component({
  selector: TspResultsComponent.ngSelectorName,
  templateUrl: './tsp-results.component.html',
  styleUrls: ['./tsp-results.component.scss'],
  standalone: true,
  encapsulation,
  schemas,
  imports: [
    IonicModule,
    MobxAngularModule,
    NgIf,
    NgForOf,
    TranslateModule,
    DecimalPipe
  ],
})
@CustomElement()
export class TspResultsComponent extends CustomElementBaseComponent {
  public static override readonly customElementName: string = 'app-tsp-results';
  public static override readonly ngSelectorName: string
    = `${CustomElementBaseComponent.ngPrefix}-${TspResultsComponent.customElementName}`;

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
}
