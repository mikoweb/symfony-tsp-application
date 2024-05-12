export class LocationDto {
  constructor(
    public readonly lat: number,
    public readonly lng: number,
    public readonly name: string,
  ) {}
}
