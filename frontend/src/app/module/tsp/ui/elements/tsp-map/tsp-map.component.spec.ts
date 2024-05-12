import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { TspMapComponent } from './tsp-map.component';
import { testProviders } from '@app/core/application/test/test-providers';

describe('TspMapComponent', () => {
  let component: TspMapComponent;
  let fixture: ComponentFixture<TspMapComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ TspMapComponent ],
      imports: [IonicModule.forRoot()],
      providers: testProviders,
    }).compileComponents();

    fixture = TestBed.createComponent(TspMapComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
